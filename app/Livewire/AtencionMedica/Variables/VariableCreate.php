<?php

namespace App\Livewire\AtencionMedica\Variables;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\ConsultaExterna\Variable;
use Illuminate\Support\Facades\DB;

class VariableCreate extends Component
{
    #[Validate]
    public $nombre, $tipo;

    public function render()
    {
        return view('livewire.atencion-medica.variables.variable-create');
    }

    public function rules()
    {
        return [
            'nombre' => 'required',
            'tipo' => 'required', 
        ];
    }

    #[On('guardarVaribleAsignada')] 
    public function guardarVaribleAsignada(){
        $this->validate();

        DB::beginTransaction();
        try{ 
            $existe = Variable::where('nombre', $this->nombre)
            ->where('tipo', $this->tipo) 
            ->exists();

            if ($existe) {
                $this->dispatch('variable_duplicada');
                DB::rollBack();
                
            }else{
                $variable = Variable::create([
                    'nombre' => $this->nombre,
                    'tipo' => strtolower($this->tipo),
                    'id_user_created' => auth()->user()->id,
                ]); 
                    
                $variableId = $variable->id;
                DB::commit(); 
                
                $this->dispatch('pg:eventRefresh-variableTable');
                $this->dispatch('cerrarModal');
                $this->dispatch('guardar_variable');
                
            
                $this->reset('nombre', 'tipo');
            }
 
        } catch (\Throwable $th) {  
            DB::rollBack();
            $this->reset('nombre', 'tipo');
            $this->dispatch('error');
        }
    }

     public function cancelar_registro(){
        $this->reset('nombre', 'tipo');
        $this->dispatch('cerrarModal');
    }
}
