<?php

namespace App\Livewire\Farmacia\Farmacia\Reingreso;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Farmacia\Reingreso;
use App\Models\Farmacia\ReingresoMedicamento;
use App\Services\ListaUnidades;
use Carbon\Carbon;

class ReingresoMain extends Component
{
    public $unidad, $nro_comprobante, $fecha_reingreso, $paciente, $nro_habitacion, $piso, $observacion, $codigo_reingreso;

    public  $medicamentos;
    public $cantidad, $nombreMedicamento, $formaFarmaceutica, $fecha_vencimiento, $concentracion, $motivo_reingreso;
    public $medicamentosAgregados = [];
    public $unidades;
    public $id_persona, $nombreCompleto, $ci, $matricula;

    public $origenReingreso;

    public function render()
    {
        return view('livewire.farmacia.farmacia.reingreso.reingreso-main');
    }

    public function registrar_reingreso()
    {
        return redirect()->route('farmacia.reingresos.create');
    }

    #[On('verReingresoMedicamentos')]
    public function verReingresoMedicamentos($data)
    {
        $this->reseteoDato();
        $this->mostrarReingreso($data);
        $this->dispatch('modalVerReingresoMedicamento');
    }

    public function obtenerNombreUnidad($idUnidad)
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidad)['nombre'];
    }


    public function mostrarReingreso($data)
    {
        $reingreso = Reingreso::with('detallesMedicamento', 'paciente', 'usuarioCreador', 'usuarioEditor')
            ->find($data);
        if ($reingreso) {
            $this->nombreCompleto = $reingreso->paciente ? implode(' ', array_filter([$reingreso->paciente->p_apellido, $reingreso->paciente->s_apellido, $reingreso->paciente->nombres])) : null;
            $this->ci = $reingreso->paciente ? implode(' - ', array_filter([$reingreso->paciente->ci, $reingreso->paciente->complemento])) : null;
            $this->matricula = $reingreso->paciente ? $reingreso->paciente->matricula_seguro : null;
            $this->codigo_reingreso = $reingreso->codigo_reingreso . ' / ' . Carbon::parse($reingreso->fecha_ingreso)->Format('Y');
            $this->fecha_reingreso = Carbon::parse($reingreso->fecha_ingreso)->Format('d /m/ Y ');

            $this->nro_habitacion = $reingreso->paciente ? $reingreso->nro_habitacion : null;
            $this->piso = $reingreso->paciente ? $reingreso->piso : null;
            $this->motivo_reingreso = $reingreso->motivo_reingreso;
            $this->observacion = $reingreso->observacion;
            $this->unidad = $this->obtenerNombreUnidad($reingreso->id_unidad);
            $this->origenReingreso = $reingreso->id_paciente !== null ? true : false;
            $reingresosDetalles = ReingresoMedicamento::with('medicamentoReingresoMedicamento')->where('id_reingreso', $reingreso->id)
                ->get();
            // dd($reingresosDetalles);
            foreach ($reingresosDetalles as $reingresos) {
                $this->medicamentosAgregados[] = [
                    'codigo_medicamento' => $reingresos->medicamentoReingresoMedicamento->codigo_medicamento,
                    'nombre' => $reingresos->medicamentoReingresoMedicamento->nombre,
                    'forma_farmaceutica' => $reingresos->medicamentoReingresoMedicamento->forma_farmaceutica,
                    'concentracion' => $reingresos->medicamentoReingresoMedicamento->concentracion,
                    'fecha_vencimiento' => $reingresos->fecha_vencimiento,
                    'cantidad' => $reingresos->cantidad_ingreso,
                ];
            }
        }
    }

    public function reseteoDato()
    {
        $this->reset(
            'medicamentosAgregados',
            'nombreCompleto',
            'ci',
            'matricula',
            'codigo_reingreso',
            'fecha_reingreso',
            'nro_habitacion',
            'piso',
            'motivo_reingreso',
            'observacion',
            'unidad'
        );
    }

    #[On('verPDF')]
    public function verPDF($rowId)
    {
        $url = route('farmacia.farmacia.reingresos.pdf', ['modo' => 'ver', 'id_reingreso' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");
    }
}
