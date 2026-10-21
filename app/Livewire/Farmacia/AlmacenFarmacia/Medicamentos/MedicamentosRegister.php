<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\Medicamentos;

use App\Models\Administracion\LinkServicio;
use Livewire\Component;
use App\Models\Farmacia\Medicamento;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Http;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\User;
use Livewire\Attributes\On;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Services\ListaCuentasContables;
use Carbon\Carbon;

class MedicamentosRegister extends Component
{

    #[Validate]
    public $cod_medicamento, $medicamento, $forma_farmaceutica, $concentracion, $stock_minimo_almacen,
        $restringido, $cuenta_contable, $id_cuenta_cont;

    public $datos = [];

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.medicamentos.medicamentos-register');
    }

    public function mount()
    {
        $this->restringido = false;
        $this->datos = $this->listasContables();
        if ($this->datos === null) {
            $this->datos = [];
        }

        $this->dispatch('cargarIdCuentaContable', id: null);
    }

    public function listasContables()
    {
        $listaCuentas = new ListaCuentasContables();
        return $listaCuentas->listaCuentasContables();
    }

    public function rules()
    {
        return [
            'cod_medicamento' => 'required|string|max:8',
            'medicamento' => 'required',
            'forma_farmaceutica' => 'required',
            'concentracion' => 'required',
            'restringido' => 'required',
            'id_cuenta_cont' => 'required',
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

    #[On('cancelarRegistroMedicamento')]
    public function cancelarRegistroMedicamento()
    {
        $this->resetearValores();
    }

    public function resetearValores()
    {
        $this->restringido = false;
        $this->reset('cod_medicamento', 'medicamento', 'forma_farmaceutica',  'concentracion', 'id_cuenta_cont', 'cuenta_contable', 'datos', 'stock_minimo_almacen');
    }

    public function preguntarGuardarMedicamento()
    {
        //dd($this->id_cuenta_cont);
        $this->validate();
        $this->dispatch('preguntaGuardar');
    }

    #[On('guardarMedicamento')]
    public function guardarMedicamento()
    {
        $this->validate();
        try {
            $existe = Medicamento::where('codigo_medicamento', $this->cod_medicamento)->exists();
            if ($existe) {
                $this->dispatch('medicamento_existe');
                return;
            } else {
                $medicamento = Medicamento::create([
                    'codigo_medicamento' => $this->cod_medicamento,
                    'nombre' => $this->medicamento,
                    'forma_farmaceutica' => $this->forma_farmaceutica,
                    'restringido' => $this->restringido,
                    'concentracion' => $this->concentracion,
                    'estado' => true,
                    'stock_minimo_almacen' => $this->stock_minimo_almacen,
                    'id_cuenta_contable' => $this->id_cuenta_cont,
                    'id_user_created' => auth()->user()->id,
                ]);


                if ($medicamento) {
                    $this->dispatch('medicamento_registrado_g');
                    $this->dispatch('pg:eventRefresh-MedicamentosTable');
                    // inicio notificacion
                    $nombre_usuario = $medicamento->usuarioCreador->name;
                    $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
                    $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                    foreach ($receptores as $receptor) {
                        NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 2,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' =>  $receptor->model_id,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'FARMACIA -  NUEVO MEDICAMENTO',
                            'mensaje' => 'Nuevo  medicamento registrado con codigo: ' . $this->cod_medicamento . ', nombre: ' . $this->medicamento . ', ' . $this->forma_farmaceutica . ', ' . $this->restringido . ', ' . $this->concentracion . '. ' .
                                ' Realizado por el usuario: ' . $nombre_usuario . '. En fecha: ' . Carbon::now()->format('d/m/Y'),
                        ]);
                    }
                }
                $this->datos = [];
                $this->resetearValores();
                $this->dispatch('cerrarModalRegistrarNuevoMedicamento');
            }
        } catch (\Throwable $th) {
            //dd('error');
            $this->dispatch('cerrarModalRegistrarNuevoMedicamento');
            $this->dispatch('medicamento_error');
        }
    }
}
