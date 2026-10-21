<?php

namespace App\Livewire\Administracion\Unidades;

use Livewire\Component;
use App\Models\Administracion\Unidad;
use Illuminate\Support\Facades\Auth;

class UnidadesRegister extends Component
{   
    public $nombre;
    public $sigla;
    public $estado;
    public $unidDependiente;
    public $unidades = [];

    protected $listeners = ['crearNuevaUnidad','cancelarUnidad'];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'sigla' => 'required|string|max:255',
        'estado' => 'required|boolean',
        //'unidDependiente' => 'nullable|exists:administracion.unidad,id', // Si es necesario que sea una unidad válida
    ];
    
    public function render()
    {   
        $this->unidades = Unidad::all();
        return view('livewire.Administracion.unidades.unidades-register');
    }
    public function crearNuevaUnidad(){
        if($this->unidDependiente==null){
            $this->unidDependiente=null;
        }else{
            $this->unidDependiente=(int) $this->unidDependiente;
        }
        $unidad1 = [
            'nombre' => $this->nombre,
            'sigla' => $this->sigla,
            'estado' => (bool) $this->estado,
            //'id_unidad_dep' => $this->unidDependiente, // Se debe capturar el id de la unidad dependiente
            'id_unidad_dep' => $this->unidDependiente,
            'id_user_created' => Auth::id(), // Asignar el id del usuario que crea
            'id_user_updated' => Auth::id(), // Puede ser el mismo al principio, si no hay actualización
        ];
        //dd($unidad1);
        $unidad = Unidad::create($unidad1);
        if ($unidad) {
            //$this->dispatch('unidadCreada');
            
            return redirect()->route('administracion.unidades.index')->with('evento', 'unidadCreada');
        } else {
            $this->dispatch('unidadCancelada');
        }
    }
    public function cancelarUnidad(){
        return redirect()->route('administracion.unidades.index')->with('evento', 'unidadCancelada');
    }

    public function obtenerUnidades(){
        //dd('dentra');
        //$this->unidades = Unidad::all();
    }
}
