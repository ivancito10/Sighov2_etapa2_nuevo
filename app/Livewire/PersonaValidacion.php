<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Administracion\Persona;

class PersonaValidacion extends Component
{
    public function render()
    {
        return view('livewire.persona-validacion');
    }

    public function validarPersona($ciPersona, $complemento){
        $persona = Persona::where('ci', $ciPersona)->where('complemento', $complemento)->get();
        
        if($persona){
            $this->dispatch('personaId', $persona->id); 
        }
        else{
            $this->dispatch('personaId', false);
        }
    }
}
