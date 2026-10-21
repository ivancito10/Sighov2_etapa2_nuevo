<?php

namespace App\Livewire\AtencionMedica\EspecialidadVariable;

use Livewire\Component;
use App\Services\EspecialidadService;
use App\Models\ConsultaExterna\Variable; 
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use App\Models\ConsultaExterna\EspecialidadVariable;

class EspecialidadVariableCreate extends Component
{
    public $id_especialidad, $nombre_especialidad;

    public function render()
    {
        return view('livewire.atencion-medica.especialidad-variable.especialidad-variable-create');
    }

    public function mount()
    {
       
    }
 
    #[On('especialidadSeleccionada')] 
    public function recibirEspecialidad($data)
    { 
        $this->id_especialidad = $data['id'];

         

        $this->mostrarEspecialidad(); 
    }
    public function mostrarEspecialidad(){
        $service = new EspecialidadService();
        $especialidades = $service->obtenerEspecialidades(); 
        $this->nombre_especialidad = $especialidades->get($this->id_especialidad);
    }

    //AGREGAR
    #[On('crearEspVariable')] 
    public function crearEspVariable($data)
    {
        $variable = Variable::find($data); 
        if ($variable->estado == true) { 
            $existeRegistro = EspecialidadVariable::where('id_especialidad', $this->id_especialidad)
                ->where('id_variable', $data)
                ->exists();  
            //dd($existeRegistro);

            if ($existeRegistro) { 
                $this->dispatch('variableDuplicada'); 
            }else{
                $espVariableNuevo = EspecialidadVariable::create([
                    'id_especialidad' => $this->id_especialidad,
                    'id_variable' => $data, 
                    'id_user_created' => auth()->user()->id,
                ]);

                
                if ($espVariableNuevo) {
                    $this->dispatch('agregadoVariable');
                }
            }
 
            
            
        } else {
             
            $this->dispatch('estadoFalse');
        }
    }

    //QUITAR
    #[On('quitarEspVariable')] 
    public function quitarEspVariable($data)
    {
        //dd($this->id_especialidad, $data);
        $espVariable = EspecialidadVariable::where('id_especialidad', $this->id_especialidad)
        ->where('id_variable', $data)
        ->delete();
        $this->dispatch('eliminarVariable');

    }
}
