<?php

namespace App\Livewire\AlmacenGeneral\Proveedores;

use App\Models\Farmacia\InstitucionExterna;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;

class ProveedoresMain extends Component
{
    public $razonSocial, $nit, $registroSanitario, $telefono, $correo, $direccion, $nro, $zona;
    public $zonasSelect, $id_zona, $estado;
    public $idProveedor, $fechaCreado, $userCreado, $fechaEditado, $userEdit, $fueEditado = false;

    public function render()
    {
        return view('livewire.almacen-general.proveedores.proveedores-main');
    }

    public function mount()
    {
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
    }

    public function abrirModalRegistroProveedor()
    {
        $this->dispatch('modalRegistrarNuevoProveedorAlmacenGeneral');
    }

    #[On('verProveedor')]
    public function verProveedor($data)
    {
        if ($data) {
            $this->mostrarProveedor($data);
            $this->dispatch('modalVerProveedor');
        }
    }

    public function mostrarProveedor($id)
    {
        if ($id) {
            $proveedor = InstitucionExterna::with('residenciaInstitucionExterna', 'residenciaInstitucionExterna.zonaResidencia', 'usuarioCreador', 'usuarioEditor')->find($id);
            $this->razonSocial = $proveedor->razon_social;
            $this->nit = $proveedor->nit;
            $this->registroSanitario = $proveedor->registro_sanitario;
            $this->telefono = $proveedor->telefono;
            $this->correo = $proveedor->correo;
            $this->direccion = $proveedor->residenciaInstitucionExterna->direccion;
            $this->nro = $proveedor->residenciaInstitucionExterna->nro_vivienda;
            $this->zona = $proveedor->residenciaInstitucionExterna->zonaResidencia->nombre;
            $this->id_zona = $proveedor->residenciaInstitucionExterna->zonaResidencia->id;
            $this->fechaCreado = $proveedor->create_at;
            $this->userCreado = $proveedor->usuarioCreador->name;
            $this->estado = $proveedor->estado;
            $this->fechaEditado = $proveedor->updated_at;
            if ($proveedor->id_user_updated) {
                $this->fueEditado = true;
                $this->userEdit = $proveedor->usuarioEditor?->name ?: 'SIN DATO';
            }
        }
    }

    #[On('deshabilitarProveedor')]
    public function deshabilitarProveedor($data)
    {
        $this->idProveedor = $data;
        $this->dispatch('HabilitarEstadoProveedor');
    }

    #[On('habilitarProveedor')]
    public function habilitarProveedor($data)
    {
        $this->idProveedor = $data;
        $this->dispatch('InhabilitarEstadoProveedor');
    }

    #[On('cambiarEstado')]
    public function cambiarEstado()
    {
        try {
            $proveedor = InstitucionExterna::with('residenciaInstitucionExterna', 'residenciaInstitucionExterna.zonaResidencia', 'usuarioCreador')
                ->find($this->idProveedor);
            if ($proveedor) {
                $estadoActual = $proveedor->estado;
                $proveedor->estado = !$estadoActual;
                $proveedor->save();
                $this->dispatch('pg:eventRefresh-proveedoresAlmacenGeneralTable');
                if ($proveedor->estado == true) {
                    $this->dispatch('proveedorHabilitado');
                    // para notificacion
                    $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
                    $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                    foreach ($receptores as $receptor) {
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 3,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' =>  $receptor->model_id,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'Proveedor habilitado',
                            'mensaje' => 'El usuario ' . $proveedor->usuarioCreador->name . ' HABILITÓ al proveedor ' . $proveedor->razon_social . ' en fecha: ' . Carbon::parse($proveedor->create_at)->Format('d /m/ Y '),
                        ]);
                    }
                    //fin notificacion
                } else {
                    $this->dispatch('proveedorDeshabilitado');
                    // para notificacion
                    $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
                    $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                    foreach ($receptores as $receptor) {
                        $nuevaNotificacion = NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 3,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' =>  $receptor->model_id,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'Proveedor deshabilitado',
                            'mensaje' => 'El usuario ' . $proveedor->usuarioCreador->name . ' DESHABILITÓ al proveedor ' . $proveedor->razon_social . ' en fecha: ' . Carbon::parse($proveedor->create_at)->Format('d /m/ Y '),
                        ]);
                    }
                    //fin notificacion
                }
            }
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }
}
