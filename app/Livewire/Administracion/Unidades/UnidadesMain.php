<?php

namespace App\Livewire\Administracion\Unidades;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Administracion\Unidad;

class UnidadesMain extends Component
{   
    public $idUnid;
    public $nombreUnid;
    public $siglaUnid;
    public $estadoUnid;
    public $UnidadDep;
    protected $listeners = ['actualizarUnidad','eliminarUnidad'];

    public function render()
    {   
        $this->mostrarUnidad($this->idUnid);
        return view('livewire.Administracion.unidades.unidades-main');
    }

    public function redirectToForm()
    {   //dd('dentra1');
        return redirect()->route('administracion.unidades.create');
    }
    public function mostrarUnidad($unidId)
    {
        try {
            //dd('dentra');
            $unidad = Unidad::find($unidId);
            //$this->idUnid = $unidad->nombre;
            $this->nombreUnid = $unidad->nombre;
            $this->siglaUnid = $unidad->sigla;
            $this->estadoUnid= $unidad->estadoUnid;
            $this->UnidadDep = $unidad ->id_unidad_dep;
        } catch (\Throwable $th) {
            $this->idUnid = '';
            $this->nombreUnid = '';
            $this->siglaUnid = '';
            $this->estadoUnid= '';
            $this->UnidadDep = '';
        }
    }

    #[On('editarUnidad')]
    public function editarUnidad($data)
    {   
        $this->idUnid = $data;
        $this->mostrarUnidad($this->idUnid);
        //dd($this);
        $this->dispatch('abrirModalEditar');
    }

    public function eliminarUnidad()
    {   
        try {
            $unidad = Unidad::find($this->idUnid);
            if ($unidad) {
                $unidad->delete();
                // Emitir evento de éxito para notificar la eliminación
                $this->dispatch('unidadEliminada');
                $this->dispatch('pg:eventRefresh-unidadesRegistro');
            } else {
                $this->dispatch('unidadNoEncontrada');
            }
        } catch (\Throwable $th) {
            $this->dispatch('unidadNoEliminada');
        }
    }

    #[On('confirmarEliminar')]
    public function confirmarEliminar($rowId){
        $this->idUnid = $rowId;
        $this->dispatch('confirmarEliminarDato');
    }


    public function actualizarunidad(){
        try {
            if($this->idUnid != ''){
                $unidadActualizar = Unidad::find($this->idUnid);
                $unidadActualizar->nombre = $this->nombreUnid;
                $unidadActualizar->sigla = $this->siglaUnid;
                $unidadActualizar->estado = $this->estadoUnid;
                $unidadActualizar->id_unidad_dep = $this->UnidadDep;
                $unidadActualizar->id_user_updated = auth()->user()->id;
                $unidadActualizar->save();
                $this->dispatch('unidadActualizada');              
                $this->dispatch('pg:eventRefresh-unidadesRegistro');
            } else {
                $this->dispatch('datoIncompleto');
            }        
            
        } catch (\Throwable $th) {
            $this->dispatch('unidadNoActualizada');
        }
        

    }
}
