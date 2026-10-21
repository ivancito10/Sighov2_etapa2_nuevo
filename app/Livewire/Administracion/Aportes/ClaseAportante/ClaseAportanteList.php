<?php

namespace App\Livewire\Administracion\Aportes\ClaseAportante;

use Livewire\Component;
use App\Models\Aportes\ClaseAportante;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class ClaseAportanteList extends Component
{
    use WithPagination;

    public $idClase;
    public $nombre;
    public $tipo;
    public $porcentaje;
    public $search;

    protected $listeners = ['editarClaseAportante', 'buttonDisable'];

    #[On('crearClase')]
    public function render()
    {
        $datos_clase_aportante = $this->listar();
        return view('livewire.Administracion.aportes.clase-aportante.clase-aportante-list', ['datos_clase_aportante' => $datos_clase_aportante]);
    }

    public function listar()
    {
        $datos_clase_aportante = ClaseAportante::where(function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('tipo', 'like', '%' . $this->search . '%')
                ->orWhere('porcentaje', 'like', '%' . $this->search . '%');
        })
            ->where('porcentaje', '<=', 10) // Condición para porcentaje <= 10
            ->orderBy('id', 'desc')
            ->paginate(5);
        return $datos_clase_aportante;


    }

    public function obtenerClaseAportante($idClase)
    {
        $dato_clase_aportante = ClaseAportante::find($idClase);
        if (!$dato_clase_aportante) {
            return null;
        }

        $this->idClase = $dato_clase_aportante->id;
        $this->nombre = $dato_clase_aportante->nombre;
        $this->tipo = $dato_clase_aportante->tipo;
        $this->porcentaje = $dato_clase_aportante->porcentaje;

        $this->skipRender();
        return ([
            'idClase' => $dato_clase_aportante->id,
            'nombre' => $dato_clase_aportante->nombre,
            'tipo' => $dato_clase_aportante->tipo,
            'porcentaje' => $dato_clase_aportante->porcentajea
        ]);

    }

    public function buttonDisable($idClase)
    {
        try {
            $datos_clase_aportante = ClaseAportante::find($idClase);

            if ($datos_clase_aportante) {
                $estadoActual = $datos_clase_aportante->estado;
                $datos_clase_aportante->estado = !$estadoActual;
                $datos_clase_aportante->save();
                if ($estadoActual == 'True') {
                    $this->dispatch('changeStatusDisabled');
                } else {
                    $this->dispatch('changeStatusEnabled');
                }
            }
            $this->dispatch('ufvActualizado');

        } catch (\Throwable $th) {
            $this->dispatch('ufvNoActualizado');
        }
    }


    public function editarClaseAportante()
    {
        try {
            $clase_aportante = ClaseAportante::find($this->idClase);
            if ($clase_aportante) {
                $clase_aportante->nombre = $this->nombre;
                $clase_aportante->tipo = $this->tipo;
                $clase_aportante->porcentaje = $this->porcentaje;
                $clase_aportante->save();
            }

            if ($clase_aportante) {
                $this->dispatch('ModifyCA');
            }
            $this->reset(['nombre', 'tipo', 'porcentaje']);
        } catch (\Throwable $th) {
            $this->reset(['nombre', 'tipo', 'porcentaje']);
            $this->dispatch('CancelModifyCA');

        }
    }

    public function eliminarClaseAportante($id)
    {
        $this->validate([
            'nombre' => 'required',
            'tipo' => 'required',
            'porcentaje' => 'required',
        ]);
        // Crea un nuevo registro de ClaseAportante
        ClaseAportante::where('id', $id)
            ->update(
                [
                    'estado' => false,
                ]
            );
        $this->reset([
            'nombre',
            'tipo',
            'porcentaje',
        ]);
        $this->dispatch('crearClase');
    }
}
