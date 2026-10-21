<?php

namespace App\Livewire\AlmacenGeneral\Materiales;

use App\Models\AlmacenGeneral\Material;
use App\Services\ListaCuentasContables;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class MaterialesEdit extends Component
{
    public $idMaterial;
    #[Validate]
    public $datos = [];
    #[Validate]
    public $codigo_material;
    #[Validate]
    public $nombre;
    #[Validate]
    public $unidad;
    #[Validate]
    public $descripcion;
    #[Validate]
    public $id_cuenta_contable;
    public $nroCuentaContable;
    public $nombreCuentaContable;

    public function rules()
    {
        return [
            'codigo_material' => 'required|string|max:8',
            'nombre' => 'required',
            'unidad' => 'required',
            'descripcion' => 'required',
            'id_cuenta_contable' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'codigo_material.required' => 'Este campo es obligatorio',
            'codigo_material.max' => 'El código no puede tener más de 8 caracteres',
        ];
    }

    public function render()
    {
        return view('livewire.almacen-general.materiales.materiales-edit');
    }

    public function mount()
    {
        $this->datos = $this->listasContables();
    }

    #[On('editarMaterial')]
    public function editarMaterial($data)
    {
        $this->idMaterial = $data;
        $this->mostrarMaterial($this->idMaterial);
        $this->dispatch('editarMaterialModal');
        $this->dispatch('cargarIdCuentaContable', id: $this->id_cuenta_contable);
    }

    public function listasContables()
    {
        $listaCuentas = new ListaCuentasContables();
        return $listaCuentas->listaCuentasContables();
    }

    public function mostrarMaterial($id)
    {
        if ($id) {
            $material = Material::find($id);
            $this->codigo_material = $material->codigo;
            $this->nombre = $material->nombre_material;
            $this->unidad = $material->unidad;
            $this->descripcion = $material->descripcion;
            $this->id_cuenta_contable = $material->id_nro_cuenta;
            $id_cuenta_contable = $material->id_nro_cuenta;
            $cuenta = collect($this->datos)->firstWhere('id', $id_cuenta_contable);
            $this->nroCuentaContable = $cuenta['nro_cuenta'];
            $this->nombreCuentaContable = $cuenta['nombre'];
        }
    }

    #[On('guardarEdicionMaterial')]
    public function guardarEdicionMaterial()
    {
        try {
            $mat = Material::find($this->idMaterial);
            if ($mat) {
                $existe = Material::where('codigo', $this->codigo_material)
                    ->whereNot('id', $this->idMaterial)
                    ->where('estado', true)
                    ->exists();
                
                if (!$existe) {
                    
                    $mat->codigo = $this->codigo_material;
                    $mat->nombre_material = $this->nombre;
                    $mat->unidad = $this->unidad;
                    $mat->descripcion = $this->descripcion;
                    $mat->id_nro_cuenta = $this->id_cuenta_contable;
                    $mat->id_user_updated = auth()->user()->id;
                    $mat->save();

                    $this->resetearValores();
                    $this->dispatch('materialEditadoCorrectamente');
                    $this->dispatch('pg:eventRefresh-MaterialesTable');
                    $this->dispatch('cerrarModalRegistrarNuevoMaterial');
                } else {
                    $this->dispatch('materialYaExiste');
                    return;
                }
            }
        } catch (\Throwable $th) {
            
            $this->dispatch('cerrarModalRegistrarNuevoMaterial');
            $this->dispatch('material_error');
        }
    }

    public function preguntarEditarMaterial()
    {
        $this->validate();
        $this->dispatch('preguntaGuardarEditar');
    }

    #[On('cancelarEdicionMaterial')]
    public function cancelarEdicionMaterial()
    {
        $this->resetearValores();
    }

    public function resetearValores()
    {
        $this->reset('codigo_material', 'nombre', 'unidad',  'descripcion', 'id_cuenta_contable');
    }
}
