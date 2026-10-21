<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\Medicamentos;

use App\Models\Farmacia\Medicamento;
use App\Services\ListaCuentasContables;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class MedicamentosEdit extends Component
{

    public $idMedicamento;
    #[Validate]
    public $datos = [];
    #[Validate]
    public $codigo_medicamento;
    #[Validate]
    public $nombre;
    #[Validate]
    public $forma_farmaceutica;
    #[Validate]
    public $concentracion;
    #[Validate]
    public $restringido;
    #[Validate]
    public $id_cuenta_contable;
    #[Validate]
    public $stock_minimo_almacen;
    public $nroCuentaContable;
    public $nombreCuentaContable;

    public function rules()
    {
        return [
            'codigo_medicamento' => 'required|string|max:8',
            'nombre' => 'required',
            'forma_farmaceutica' => 'required',
            'concentracion' => 'required',
            'restringido' => 'required',
            'id_cuenta_contable' => 'required',
            'stock_minimo_almacen' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'cod_medicamento.required' => 'Este campo es obligatorio',
            'cod_medicamento.max' => 'El código no puede tener más de 8 caracteres',
        ];
    }

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.medicamentos.medicamentos-edit');
    }

    public function mount()
    {
        //$this->mostrarMedicamento();
        $this->datos = $this->listasContables();
    }

    #[On('editarMedicamento')]
    public function editarMedicamento($data)
    {
        $this->idMedicamento = $data;
        $this->mostrarMedicamento($this->idMedicamento);
        $this->dispatch('editarMedicamentoModal');
        $this->dispatch('cargarIdCuentaContable', id: $this->id_cuenta_contable);
    }

    public function listasContables()
    {
        $listaCuentas = new ListaCuentasContables();
        return $listaCuentas->listaCuentasContables();
    }

    public function mostrarMedicamento($id)
    {
        if ($id) {
            $medicamento = Medicamento::find($id);
            $this->codigo_medicamento = $medicamento->codigo_medicamento;
            $this->nombre = $medicamento->nombre;
            $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
            $this->concentracion = $medicamento->concentracion;
            $this->restringido = $medicamento->restringido;
            $this->id_cuenta_contable = $medicamento->id_cuenta_contable;
            $this->stock_minimo_almacen = $medicamento->stock_minimo_almacen;
            
            $id_cuenta_contable = $medicamento->id_cuenta_contable;

            // $this->recibir_cuenta_contable();
            // Busca el código relacionado en las cuentas contables
            $cuenta = collect($this->datos)->firstWhere('id', $id_cuenta_contable);
            $this->nroCuentaContable = $cuenta['nro_cuenta'];
            $this->nombreCuentaContable = $cuenta['nombre'];
            //dd($cuenta);
            /*$this->id_cuenta = $cuenta ? $cuenta['id'] : null;
            $this->cuenta_contable = $cuenta ? $cuenta['codigo'] : 'Cuenta no encontrada';
            $this->nombre_cuenta = $cuenta ? $cuenta['nombre'] : 'Nombre no encontrado';*/
        }
    }

    #[On('guardarEdicionMedicamento')]
    public function guardarEdicionMedicamento()
    {
        try {
            $med = Medicamento::find($this->idMedicamento);
            if ($med) {
                $existe = Medicamento::where('codigo_medicamento', $this->codigo_medicamento)
                    ->whereNot('id', $this->idMedicamento)
                    ->exists();

                if (!$existe) {
                    $med->codigo_medicamento = $this->codigo_medicamento;
                    $med->nombre = $this->nombre;
                    $med->forma_farmaceutica = $this->forma_farmaceutica;
                    $med->concentracion = $this->concentracion;
                    $med->restringido = $this->restringido;
                    $med->id_cuenta_contable = $this->id_cuenta_contable;
                    $med->id_user_updated = auth()->user()->id;
                    $med->stock_minimo_almacen = $this->stock_minimo_almacen;

                    $med->save();
                    $this->resetearValores();
                    $this->dispatch('medicamentoEditadoCorrectamente');
                    $this->dispatch('pg:eventRefresh-MedicamentosTable');
                    $this->dispatch('cerrarModalRegistrarNuevoMedicamento');
                } else {
                    $this->dispatch('medicamentoYaExiste');
                    return;
                }
            }
        } catch (\Throwable $th) {
            $this->dispatch('cerrarModalRegistrarNuevoMedicamento');
            $this->dispatch('medicamento_error');
        }
    }

    public function preguntarEditarMedicamento()
    {
        $this->validate();
        $this->dispatch('preguntaGuardarEditar');
    }

    #[On('cancelarEdicionMedicamento')]
    public function cancelarEdicionMedicamento()
    {
        $this->resetearValores();
    }

    public function resetearValores()
    {
        $this->restringido = false;
        $this->reset('codigo_medicamento', 'nombre', 'forma_farmaceutica',  'concentracion', 'id_cuenta_contable', 'stock_minimo_almacen');
    }
}
