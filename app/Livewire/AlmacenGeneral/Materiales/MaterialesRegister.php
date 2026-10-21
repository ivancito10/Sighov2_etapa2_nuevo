<?php

namespace App\Livewire\AlmacenGeneral\Materiales;

use App\Models\AlmacenGeneral\Material;
use Livewire\Attributes\On;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\ListaCuentasContables;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Validate;

class MaterialesRegister extends Component
{
    public $codigo, $nombre, $descripcion, $estado, $unidad, $id_cuenta_cont, $cuenta_contable, $cuentas;
    public $datos;

    public function rules()
    {
        return [
            'codigo' => 'required|string|max:8',
            'nombre' => 'required',
            'unidad' => 'required',
            'descripcion' => 'required',
            'id_cuenta_cont' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'Este campo es obligatorio',
            'codigo.max' => 'El código no puede tener más de 8 caracteres',
        ];
    }

    public function render()
    {
        return view('livewire.almacen-general.materiales.materiales-register');
    }

    public function mount()
    {
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

    public function obtenerNroCuenta($dato)
    {
        $cuentaSeleccinada = collect($this->cuentas)
            ->where('id', $dato)
            ->first();
        // dd($cuentaSeleccinada);
        return $cuentaSeleccinada['codigo'] ?? null;
    }


    #[On('cancelarRegistroMaterial')]
    public function cancelarRegistroMaterial()
    {
        $this->resetearValores();
        $this->dispatch('material_array_reset');
        $this->dispatch('cerrarModalRegistrarNuevoMaterial');
    }

    public function resetearValores()
    {
        $this->datos = [];
        $this->reset('codigo', 'nombre', 'unidad',  'descripcion', 'id_cuenta_cont', 'cuenta_contable');
    }

    public function preguntarGuardarMaterial()
    {
        //dd($this->id_cuenta_cont);
        if(!$this->verificarCuentaContable()){
            $this->dispatch('noTieneCuentaContable');
            return;
        };     
        $this->validate();
        $this->dispatch('preguntaGuardar');
    }

    public function verificarCuentaContable()
    {
        $tieneCuenta = false;
        if ($this->id_cuenta_cont != null) {
            $tieneCuenta = true;
        }
        return $tieneCuenta;
    }


    #[On('guardarMaterial')]
    public function guardarMaterial()
    {
        try {
            DB::beginTransaction();
            if (strlen($this->codigo) > 6) {
                $this->dispatch('codigoLArgoMateriales');
                return;
            }
            $existeMaterial = Material::where('codigo', $this->codigo)
                ->where('nombre_material', $this->nombre)
                ->first();
            if (!$existeMaterial) {
                if (!empty($this->codigo) && !empty($this->unidad) && !empty($this->id_cuenta_cont) && !empty($this->nombre) && !empty($this->descripcion)) {
                    $material = Material::create([
                        'codigo' => $this->codigo,
                        'unidad' => $this->unidad,
                        'id_nro_cuenta' => $this->id_cuenta_cont,
                        'nro_cuenta' => $this->obtenerNroCuenta($this->id_cuenta_cont),
                        'descripcion' => $this->descripcion,
                        'nombre_material' => $this->nombre,
                        'estado' => true,
                        'id_user_created' => auth()->user()->id

                    ]);
                    DB::commit();
                    $this->dispatch('registradoMateriales');
                    $this->dispatch('pg:eventRefresh-MaterialesTable');
                    $this->dispatch('cerrarModalRegistrarNuevoMaterial');
                    $this->dispatch('material_registrado_g');
                    $this->reseteoMateriales();
                } else {
                    $this->dispatch('faltanDatosMateriales');
                }
            } else {
                $this->dispatch('yaExisteMateriales');
            }
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error('Error guardando material: ' . $e->getMessage());
            $this->dispatch('errorCatch');
        }
    }

    public function reseteoMateriales()
    {
        $this->codigo = null;
        $this->unidad = null;
        $this->id_cuenta_cont = null;
        $this->nombre = null;
        $this->descripcion = null;
    }
}
