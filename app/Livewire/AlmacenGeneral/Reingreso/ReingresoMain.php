<?php

namespace App\Livewire\AlmacenGeneral\Reingreso;

use App\Models\AlmacenGeneral\Reingreso;
use App\Models\AlmacenGeneral\ReingresoMaterial;
use App\Services\ListaUnidades;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class ReingresoMain extends Component
{
    public $unidad, $nro_comprobante, $fecha_reingreso, $paciente, $nro_habitacion, $piso, $observacion, $codigo_reingreso;

    public $materiales;
    public $cantidad, $nombreMaterial, $unidadMaterial, $fecha_vencimiento, $descripcion, $motivo_reingreso;
    public $materialesAgregados = [];
    public $unidades;
    public $id_persona, $nombreCompleto, $ci, $matricula;

    public $origenReingreso;

    public function render()
    {
        return view('livewire.almacen-general.reingreso.reingreso-main');
    }

    public function registrar_reingreso()
    {
        return redirect()->route('almacen-general.reingresos.create');
    }

    #[On('verReingresoMateriales')]
    public function verReingresoMateriales($data)
    {
        $this->reseteoDato();
        $this->mostrarReingreso($data);
        $this->dispatch('modalVerReingresoMaterial'); 
    }

    public function obtenerNombreUnidad($idUnidad)
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidad)['nombre'];
    }


    public function mostrarReingreso($data)
    {
        $reingreso = Reingreso::with('detallesMaterial', 'paciente', 'usuarioCreador', 'usuarioEditor')
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
            $reingresosDetalles = ReingresoMaterial::with('materialReingresoMaterial')->where('id_reingreso', $reingreso->id)
                ->get();
            // dd($reingresosDetalles);
            foreach ($reingresosDetalles as $reingresos) {
                $this->materialesAgregados[] = [
                    'codigo' => $reingresos->materialReingresoMaterial->codigo,
                    'nombre_material' => $reingresos->materialReingresoMaterial->nombre_material,
                    'unidad' => $reingresos->materialReingresoMaterial->unidad,
                    'descripcion' => $reingresos->materialReingresoMaterial->descripcion,
                    'fecha_vencimiento' => $reingresos->fecha_vencimiento,
                    'cantidad' => $reingresos->cantidad_ingreso,
                ];
            }
        }
    }

    public function reseteoDato()
    {
        $this->reset(
            'materialesAgregados',
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
        $url = route('almacen_general.reingresos.pdf', ['modo' => 'ver', 'id_reingreso' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");
    }

}
