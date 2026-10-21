<?php

namespace App\Livewire\AlmacenGeneral\Materiales;

use App\Models\AlmacenGeneral\Material;
use Livewire\Attributes\On;
use App\Services\ListaCuentasContables;
use Livewire\Component;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialesMain extends Component
{
    public $codigo, $nombre, $descripcion, $estado, $unidad, $nro_cuenta, $id_nro_cuenta, $estadoMostrar;

    public $id_material;
    public $cuentas = [];

    public function render()
    {
        return view('livewire.almacen-general.materiales.materiales-main');
    }
    
    public function mount()
    {
        $this->obtenerCuentasContables();
    }

    #[On('verControlMateriales')]
    public function verControlMateriales($data)
    {
        $this->dispatch('abrirmodalVerMaterialess');

        $this->mostrarMaterial($data);
    }

    public function mostrarMaterial($data)
    {
        $material = Material::find($data);
        if ($material) {
            $this->id_nro_cuenta = $material->id_nro_cuenta;
            $this->estadoMostrar = $material->estado;
            $this->codigo = $material->codigo;
            $this->nombre = $material->nombre_material;
            $this->descripcion = $material->descripcion;
            $this->unidad = $material->unidad;
            $this->nro_cuenta = $this->obtenerNroCuenta($this->id_nro_cuenta);
        }
    }

    public function obtenerCuentasContables()
    {
        $datosCuentas = new ListaCuentasContables();
        $this->cuentas = collect($datosCuentas->listaCuentasContables())
        //dd($this->cuentas);
            ->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'codigo' => $item['codigo'],
                    'nro_cuenta' => $item['nro_cuenta'],
                    'nombre_cuenta' => $item['nombre']
                ];
            })->toArray();
        //dd($datosCuentas->listaCuentasContables());
    }

    public function obtenerNroCuenta($dato)
    {
        $cuentaSeleccinada = collect($this->cuentas)
            ->where('id', $dato)
            ->first();
        //dd($cuentaSeleccinada);
        return $cuentaSeleccinada ? $cuentaSeleccinada['nro_cuenta'] . ' - ' . $cuentaSeleccinada['nombre_cuenta'] : null;
    }

    public function reseteoMateriales()
    {
        $this->codigo = null;
        $this->unidad = null;
        $this->id_nro_cuenta = null;
        $this->nro_cuenta = null;
        $this->nombre = null;
        $this->descripcion = null;
    }

    #[On('editarControlMateriales')]
    public function editarControlMateriales($data)
    {
        $this->id_material = $data;
        $this->dispatch('editarMaterialModal');
        $this->mostrarMaterial($data);
    }

    #[On('editandoMateriales')]
    public function editandoMateriales()
    {
        //dd('entra');
        try {
            DB::beginTransaction();
            if (!empty($this->codigo) && !empty($this->unidad) && !empty($this->id_nro_cuenta) && !empty($this->nombre) && !empty($this->descripcion)) {
                $existeMaterial = Material::where('codigo', $this->codigo)
                    ->where('nombre_material', $this->nombre)
                    ->first();
                if (!$existeMaterial) {
                    $material = Material::find($this->id_material);
                    if ($material) {
                        $material->codigo = $this->codigo;
                        $material->unidad = $this->unidad;
                        $material->nombre_material = $this->nombre;
                        $material->descripcion = $this->descripcion;
                        $material->id_nro_cuenta = $this->id_nro_cuenta;
                        $material->nro_cuenta = $this->obtenerNroCuenta($this->id_nro_cuenta);
                        $material->id_user_updated = auth()->user()->id;
                        $material->save();
                        DB::commit();
                    }
                    $this->dispatch('registradoMaterialesEditar');
                    $this->dispatch('pg:eventRefresh-MaterialesTable');
                    $this->reseteoMateriales();
                } else {
                    $this->dispatch('yaExisteMaterialesEditar');
                }
            } else {
                $this->dispatch('faltanDatosMaterialesEditar');
            }
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error('Error editando material: ' . $e->getMessage());
            $this->dispatch('errorCatchEditar');
        }
    }

    #[On('cambiarEstadoMateriales')]
    public function cambiarEstadoMateriales($data)
    {
        $this->id_material = $data;
        $material = Material::find($this->id_material);
        if ($material) {
            $this->estado = $material->estado;
        }
        $this->dispatch('cambiandoEstadoMateriales');
    }

    #[On('actualizarEstadoMateriales')]
    public function actualizarEstadoMateriales($confirmado = null)
    {
        if ($confirmado) {
            $material = Material::find($this->id_material);
            if ($material) {
                $estadoActual = $material->estado;
                $material->estado = !$estadoActual;
                $material->save();
                $this->dispatch('pg:eventRefresh-MaterialesTable');
            }
        }
    }

    public function registrarMaterial()
    {
        $this->dispatch('modalRegistrarNuevoMaterial');
    }
}
