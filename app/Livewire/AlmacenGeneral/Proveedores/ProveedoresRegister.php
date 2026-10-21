<?php

namespace App\Livewire\AlmacenGeneral\Proveedores;

use App\Models\Administracion\Residencia;
use App\Models\Administracion\Zona;
use Livewire\Attributes\On;
use App\Models\Farmacia\InstitucionExterna;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class ProveedoresRegister extends Component
{
    #[Validate]
    public $razonSocial, $nit, $registroSanitario, $telefono, $correo, $direccion, $nro;
    public $zonasSelect, $id_zona;

    public function render()
    {
        return view('livewire.almacen-general.proveedores.proveedores-register');
    }

    public function mount()
    {
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
    }
    public function guardarProveedor()
    {
        $this->validate();
        $this->dispatch('funcionGuardarProveedor');
    }

    public function cancelarRegistroProveedor()
    {
        $this->resetearDatos();
    }

    public function rules()
    {
        return [
            'razonSocial' => 'required| string',
            'nit' => 'required|numeric|min:3',
            // 'registro_sanitario' => 'required',
            'telefono' => 'required|numeric',
            'correo' => 'required|email',
            'direccion' => 'required| string',
            'nro' => 'required |min:1',
            'id_zona' => 'required',
        ];
    }

    #[On('registrarProveedor')]
    public function registrarProveedor()
    {
        //dd('SDFSD');
        $this->validate();
        DB::beginTransaction();
        try {

            $existeProveedor = InstitucionExterna::where('razon_social', $this->razonSocial)
                ->first();
            if ($existeProveedor) {
                $this->dispatch('registroExiste');
                return;
            }
            $residencia = Residencia::create([
                'direccion' => $this->direccion,
                'nro_vivienda' => $this->nro,
                'id_zona' => $this->id_zona,
                'id_user_created' => auth()->user()->id,
            ]);

            $nuevoProveedor = InstitucionExterna::create([
                'razon_social' => $this->razonSocial,
                'nit' => $this->nit,
                'registro_sanitario' => $this->registroSanitario,
                'telefono' => $this->telefono,
                'correo' => $this->correo,
                'estado' => true,
                'id_residencia' => $residencia->id,
                'id_user_created' => auth()->user()->id,
                'proveedor_almacen_general' => true,
            ]);

            // para notificacion
            $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
            $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
            $nombreCreador = $nuevoProveedor->usuarioCreador->name;
            foreach ($receptores as $receptor) {
                $nuevaNotificacion = NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 2,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' =>  $receptor->model_id,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'Nuevo registro de proveedor',
                    'mensaje' => 'Se creó el proveedor ' . $nuevoProveedor->razon_social . ' en fecha: ' . Carbon::parse($nuevoProveedor->create_at)->Format('d /m/ Y ') . ' por el usuario: ' . $nombreCreador,
                ]);
            }
            
            //fin notificacion
            DB::commit();
            $this->dispatch('registroProveedorExitoso');
            $this->dispatch('pg:eventRefresh-proveedoresAlmacenGeneralTable');
            // $this->resetearDatos();
            $this->dispatch('cerrarModalRegistrarNuevoProveedorAlmacenGeneral');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('registroProveedorError');
        }
    }

    public function resetearDatos()
    {
        $this->reset('razonSocial', 'nit', 'registroSanitario', 'telefono', 'correo', 'direccion', 'nro', 'id_zona');
    }
}
