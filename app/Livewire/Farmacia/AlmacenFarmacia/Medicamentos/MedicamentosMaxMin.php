<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\Medicamentos;

use App\Models\ConsultaExterna\TipoConsulta;
use App\Models\Farmacia\MaxMinServicio;
use App\Models\Farmacia\Medicamento;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class MedicamentosMaxMin extends Component
{
    public $idMedicamento;
    public $codigo_medicamento;
    public $nombre;
    #[Validate]
    public $valorMaximo;
    #[Validate]
    public $valorMinimo;
    #[Validate]
    public $idTipoConsulta;
    public $tiposConsulta = [];
    public $nuevosValoresSwitch = true;
    public $listaValoresActuales = [];

    public function rules()
    {
        return [
            'valorMaximo' => ['required', 'integer', 'min:1'],
            'valorMinimo' => ['required', 'integer', 'min:1'],
            'idTipoConsulta' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'valorMaximo.required' => 'Este campo es obligatorio.',
            'valorMaximo.integer'  => 'Ingrese un número entero sin decimales ni símbolos.',
            'valorMaximo.min'      => 'El valor debe ser mayor o igual a 1.',

            'valorMinimo.required' => 'Este campo es obligatorio.',
            'valorMinimo.integer'  => 'Ingrese un número entero sin decimales ni símbolos.',
            'valorMinimo.min'      => 'El valor debe ser mayor o igual a 1.',

            'idTipoConsulta.required' => 'Este campo es obligatorio.',
        ];
    }

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.medicamentos.medicamentos-max-min');
    }

    #[On('maxMinMedicamento')]
    public function maxMinMedicamento($data)
    {
        $this->idMedicamento = $data;
        $this->mostrarMedicamentoMaxMin($this->idMedicamento);
        $this->dispatch('maxMinServicioMedicamentoModal');
    }

    public function mostrarMedicamentoMaxMin($idMedicamento)
    {
        if ($idMedicamento) {
            $medicamento = Medicamento::find($idMedicamento);
            if ($medicamento) {
                $this->codigo_medicamento = $medicamento->codigo_medicamento;
                $this->nombre = $medicamento->nombre;
                $this->tiposConsulta = TipoConsulta::where('estado', true)->get();
                $this->listaValoresActuales = $this->cargarListaValoresActuales($idMedicamento);
            }
        }
    }

    public function buscarValoresActuales()
    {
        if ($this->idTipoConsulta === "" || $this->idTipoConsulta === null) {
            $this->idTipoConsulta = null;
            $this->valorMinimo = null;
            $this->valorMaximo = null;
        } else {
            $cantidadesMedicamentoServicio = MaxMinServicio::where('id_tipo_consulta', $this->idTipoConsulta)
                ->where('id_medicamento', $this->idMedicamento)
                ->first();

            if ($cantidadesMedicamentoServicio) {
                $this->nuevosValoresSwitch = false;
                $this->valorMinimo = $cantidadesMedicamentoServicio->cantidad_minima;
                $this->valorMaximo = $cantidadesMedicamentoServicio->cantidad_maxima;
            } else {
                $this->nuevosValoresSwitch = true;
                $this->valorMinimo = 1;
                $this->valorMaximo = 1;
            }
        }
    }

    public function cargarListaValoresActuales($idMedicamento)
    {
        $listaValoresActuales = MaxMinServicio::with(['maxMinServicioTipoConsulta'])
            ->where('id_medicamento', $idMedicamento)
            ->orderBy('id_tipo_consulta', 'asc')
            ->get();

        if (count($listaValoresActuales) > 0) {
            return $listaValoresActuales;
        } else {
            return [];
        }
    }

    public function actualizarGuardarValor()
    {
        $this->validate();
        if ($this->valorMinimo > $this->valorMaximo){
            $this->dispatch('minimoMayorQueMaximo');
            return;
        }
        $this->dispatch('preguntarGuardarValor');
    }

    #[On('registrarValores')]
    public function registrarValores()
    {
        try {

            $cantidadesMedicamentoServicioExistente = MaxMinServicio::where('id_tipo_consulta', $this->idTipoConsulta)
                ->where('id_medicamento', $this->idMedicamento)
                ->first();

            if ($cantidadesMedicamentoServicioExistente) {
                $cantidadesMedicamentoServicioExistente->cantidad_maxima = $this->valorMaximo;
                $cantidadesMedicamentoServicioExistente->cantidad_minima = $this->valorMinimo;
                $cantidadesMedicamentoServicioExistente->id_user_updated = auth()->user()->id;
                $cantidadesMedicamentoServicioExistente->save();
            } else {
                $nuevosValoresGuardados = MaxMinServicio::create([
                    'id_medicamento' => $this->idMedicamento,
                    'cantidad_minima' => $this->valorMinimo,
                    'cantidad_maxima' => $this->valorMaximo,
                    'id_tipo_consulta' => $this->idTipoConsulta,
                    'id_user_created' => auth()->user()->id
                ]);
            }
            $this->listaValoresActuales = $this->cargarListaValoresActuales($this->idMedicamento);
            $this->valorMinimo = null;
            $this->valorMaximo = null;
            $this->idTipoConsulta = null;
            $this->dispatch('valoresRegistradosCorrectamente');
        } catch (\Throwable $th) {
            $this->dispatch('valoresNoRegistradosCorrectamente');
        }
    }

    public function cancelarRegistroValoresMedicamento(){
        $this->dispatch('cerrarModalRegistrarNuevoMedicamento');
    }
}
