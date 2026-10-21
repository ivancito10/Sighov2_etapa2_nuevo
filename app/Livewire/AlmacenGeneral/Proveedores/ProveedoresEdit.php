<?php

namespace App\Livewire\AlmacenGeneral\Proveedores;

use Livewire\Attributes\On;
use App\Models\Administracion\Residencia;
use App\Models\Farmacia\InstitucionExterna;
use Livewire\Attributes\Validate;
use App\Models\Administracion\Zona;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProveedoresEdit extends Component
{
    public $idProveedor;
    #[Validate]
    public $razonSocial, $nit, $registroSanitario, $telefono, $correo, $direccion, $nro, $zona;
    public $zonasSelect, $id_zona, $estado;
    public  $fechaCreado, $userCreado, $fechaEditado, $userEdit;

    public function render()
    {
        return view('livewire.almacen-general.proveedores.proveedores-edit');
    }

    public function mount()
    {
        //dd('loco que raro que no abra el jodido boton');
        $this->zonasSelect = Zona::orderBy('nombre', 'asc')->get();
    }

    #[On('editarProveedor')]
    public function editarProveedor($data)
    {
        //dd('sd');
        $this->idProveedor = $data;
        $this->mostrarProveedor($this->idProveedor);
        $this->dispatch('editarProveedorAlmacenGeneralModal');
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

    public function mostrarProveedor($id)
    {
        if ($id) {
            $proveedor = InstitucionExterna::with(
                'residenciaInstitucionExterna',
                'residenciaInstitucionExterna.zonaResidencia',
                'usuarioCreador',
                'usuarioEditor'
            )->find($id);
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
            $this->userEdit = $proveedor->usuarioEditor?->name ?: 'SIN DATO';
        }
    }

    #[On('actualizarProveedor')]
    public function actualizarProveedor()
    {
        //dd('asdsa');
        $this->validate();
        DB::beginTransaction();
        //  try {
        $proveedor = InstitucionExterna::find($this->idProveedor);
        if ($proveedor) {
            $proveedor->razon_social = $this->razonSocial;
            $proveedor->nit = $this->nit;
            $proveedor->registro_sanitario = $this->registroSanitario;
            $proveedor->telefono = $this->telefono;
            $proveedor->correo = $this->correo;
            $proveedor->id_user_updated = auth()->user()->id;
            $existeResidencia = Residencia::where('direccion', $this->direccion)
                ->where('nro_vivienda', $this->nro)
                ->where('id_zona', $this->id_zona)
                ->first();
            if ($existeResidencia) {
                $residenciaId = $existeResidencia->id;
                $proveedor->id_residencia = $residenciaId;
                $proveedor->save();
                $this->dispatch('pg:eventRefresh-proveedoresAlmacenGeneralTable');
                $this->dispatch('actualizarProveedorExitoso');
            } else {
                $residencia = Residencia::create([
                    'direccion' => $this->direccion,
                    'nro_vivienda' => $this->nro,
                    'id_zona' => $this->id_zona,
                    'id_user_created' => auth()->user()->id,
                ]);
                $proveedor->update([
                    'id_residencia' => $residencia->id,
                ]);
            }
            $proveedor->save();
            DB::commit();
            $this->dispatch('pg:eventRefresh-proveedoresAlmacenGeneralTable');
            $this->dispatch('actualizarProveedorExitoso');
            $this->cancelarProveedor();
        }
        //   } catch (\Throwable $e) {
        //       DB::rollBack();
        //       $trace = collect($e->getTrace())
        //           ->first(fn($t) => isset($t['file']) && str_contains($t['file'], base_path('app')));
        //       Log::error("Error en editar proveedores", [
        //           'USUARIO_ID' => auth()->user()->id ?? null,
        //           'ERROR'      => $e->getMessage() ?? '',
        //           'ARCHIVO'    => $trace['file'] ?? $e->getFile(),
        //           'LINEA'      => $trace['line'] ?? $e->getLine(),
        //           'FECHA_HORA' => now(),
        //       ]);
        //   }
    }

    public function preguntarEditarProveedor()
    {
        $this->validate();
        $this->dispatch('preguntaGuardarProveedor');
    }
    #[On('cancelarProveedor')]
    public function cancelarProveedor()
    {
        $this->resetearProveedores();
    }

    public function resetearProveedores()
    {
        $this->reset('razonSocial', 'nit', 'registroSanitario', 'telefono', 'correo', 'direccion', 'nro', 'id_zona');
    }
}
