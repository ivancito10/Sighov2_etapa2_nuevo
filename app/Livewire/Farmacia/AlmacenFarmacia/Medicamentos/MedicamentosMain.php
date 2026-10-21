<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\Medicamentos;

use App\Models\Administracion\LinkServicio;
use Livewire\Attributes\On;
use App\Models\Farmacia\Medicamento;
use Illuminate\Support\Facades\Http;

use Livewire\Component;
use App\Models\Administracion\NotificacionUsuarios;

use App\Models\User;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Farmacia\MaxMinServicio;
use App\Services\ListaCuentasContables;
use Carbon\Carbon;
// use App\Http\Controllers\TokensServices;
use App\Services\ObtenerToken;

class MedicamentosMain extends Component
{

    public $idMed;
    public $cod_medicamento, $medicamento, $forma_farmaceutica,  $restringido, $concentracion, $stock_minimo_almacen;
    public $cuenta_contable, $nombre_cuenta, $id_cuenta_cont, $estado, $userCrea, $fechaCreado, $userEdit, $fechaEditado;
    public $datos = [];
    public $listaValoresActuales = [];
    public $estadoFarmacia;

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.medicamentos.medicamentos-main');
    }

    public function mount()
    {
        $this->recibir_cuenta_contable();
    }

    public function registrarMedicamento()
    {
        $this->dispatch('modalRegistrarNuevoMedicamento');
    }

    public function ver_cuenta_contable()
    {
        // Aqui el servicio para mostrar en un array
        $this->recibir_cuenta_contable();
        $this->dispatch('verCuentaContable');
    }

    // ! fucnion para recibir el servicio de cuenta contable //codigo, nombre
    public function recibir_cuenta_contable()
    {
        $listaCuentas = new ListaCuentasContables();
        //dd($listaCuentas->listaCuentasContables());
        $this->datos = $listaCuentas->listaCuentasContables() !== null ? $listaCuentas->listaCuentasContables() : [];
    }

    #[On('ver_medicamento')]
    public function ver_medicamento($data)
    {
        //dd($data);
        $this->idMed = $data;
        $this->mostrarMedicamento($data);
        $this->dispatch('verMedicamento');
    }

    /*#[On('editar_medicamento')]
    public function editar_medicamento($data)
    {
        $this->idMed = $data;
        $this->mostrarMedicamento($data);
        $this->dispatch('editarMedicamentoModal');
    }*/

    #[On('cambiar_estado')]
    public function cambiar_estado($data)
    {
        $this->idMed = $data;
        $medicamento = Medicamento::find($this->idMed);
        $estado_actual = $medicamento->estado;
        if ($estado_actual == true) {
            //dd($estado_actual);
            $this->dispatch('deshabilitar_estado');
        } elseif ($estado_actual == false) {
            //dd($estado_actual);
            $this->dispatch('habilitar_estado');
        }
    }

    #[On('guardar_cambiar_estado')]
    public function guardar_cambiar_estado()
    {
        try {
            $medicamento = Medicamento::find($this->idMed);
            if ($medicamento) {
                $estadoActual = $medicamento->estado;
                $medicamento->estado = !$estadoActual;
                $medicamento->save();
                $this->dispatch('pg:eventRefresh-MedicamentosTable');

                if ($medicamento->estado == true) {
                    $this->dispatch('habilitado');
                    // inicio de notificacion - habilitado
                    $usuario = User::find(auth()->user()->id);
                    $nombre_usuario = $usuario->name;
                    $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
                    $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                    foreach ($receptores as $receptor) {
                        //
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 3,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' =>  $receptor->model_id,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'FARMACIA - MEDICAMENTO HABILITADO',
                            'mensaje' => 'El medicamento: ' . $medicamento->codigo_medicamento . ', '
                                . $medicamento->nombre . '. fue HABILITADO, por el usuario: ' . $nombre_usuario . '. En fecha: ' . Carbon::now()->format('d/m/Y'),
                        ]);
                    }
                    // fin notificacion
                } else {
                    $this->dispatch('deshabilitado');
                    $usuario = User::find(auth()->user()->id);
                    $nombre_usuario = $usuario->name;

                    // inicion notificacon - DEshabilitado
                    $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
                    $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                    foreach ($receptores as $receptor) {
                        //
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 3,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' =>  $receptor->model_id,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'FARMACIA - MEDICAMENTO DESHABILITADO',
                            'mensaje' => 'Se cambio el estado del medicamento: ' . $medicamento->codigo_medicamento . ', '
                                . $medicamento->nombre . '. A estado DESHABILITADO, realizado por el usuario: ' . $nombre_usuario . '. En fecha: ' . Carbon::now()->format('d/m/Y'),
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->dispatch('InstitucionNoActualizado');
        }
    }
    public $id_cuenta;
    public function mostrarMedicamento($id)
    {
        if ($id) {
            $medicamento = Medicamento::with('usuarioCreador', 'usuarioEditor')->find($id);
            if ($medicamento) {
                $this->cod_medicamento = $medicamento->codigo_medicamento;
                $this->medicamento = $medicamento->nombre;
                $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
                $this->concentracion = $medicamento->concentracion;
                $this->restringido = $medicamento->restringido;
                $this->id_cuenta_cont = $medicamento->id_cuenta_contable;
                $this->estado = $medicamento->estado;
                $this->stock_minimo_almacen = $medicamento->stock_minimo_almacen;
                $id_cuenta_contable = $medicamento->id_cuenta_contable;

                // $this->recibir_cuenta_contable();
                // Busca el código relacionado en las cuentas contables
                $cuenta = collect($this->datos)->firstWhere('id', $id_cuenta_contable);
                //dd($cuenta);
                $this->id_cuenta = $cuenta ? $cuenta['id'] : null;
                $this->cuenta_contable = $cuenta ? $cuenta['nro_cuenta'] : 'Cuenta no encontrada';
                $this->nombre_cuenta = $cuenta ? $cuenta['nombre'] : 'Nombre no encontrado';
                // detalles del registro jjiij
                $this->userCrea = $medicamento->usuarioCreador->name;
                $this->fechaCreado = $medicamento->created_at;
                $this->userEdit = $medicamento->usuarioEditor?->name ?: 'SIN DATO';
                $this->fechaEditado = $medicamento->updated_at;
                $this->listaValoresActuales = $this->cargarListaValoresActuales($id);
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

    #[On('habilitadoFarmacia')]
    public function habilitadoFarmacia($data)
    {
        $this->idMed = $data;
        $medicamento = Medicamento::find($this->idMed);
        $estado_actual = $medicamento->habilitado_farmacia;

        if ($estado_actual == true) {
            $this->dispatch('deshabilitar_estadoFarmacia');
        } elseif ($estado_actual == false) {
            $this->dispatch('habilitar_estadoFarmacia');
        }
    }

    #[On('guardar_estadoFarmacia')]
    public function guardar_estadoFarmacia()
    {
        $medicamento = Medicamento::find($this->idMed);
        $medicamento->habilitado_farmacia = !$medicamento->habilitado_farmacia;
        $medicamento->save();
        
        if ($medicamento->habilitado_farmacia) {
            $this->dispatch('habilitado');
        } else {
            $this->dispatch('deshabilitado');
        }

        $this->dispatch('pg:eventRefresh-MedicamentosTable');
    }
}
