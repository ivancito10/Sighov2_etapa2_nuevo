<?php

namespace App\Livewire\AtencionMedica\Variables;

use App\Models\ConsultaExterna\Variable;
use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\DB;

class VariableMain extends Component
{

    public $nombre, $tipo, $id_variable;

    public function render()
    {
        return view('livewire.atencion-medica.variables.variable-main');
    }

    public function boton_agregar(){
        $this->dispatch('modalAgregar');
    }

    #[On('cambiarEstadoVariable')]
    public function cambiarEstadoVariable($data)
    {
        //sdd($data);
        $variable = Variable::where('id', $data)->first();
        $estado_prin = $variable->estado;
          
         if ($variable) { 
            $variable->estado = !$variable->estado; 
            $variable->id_user_updated = auth()->user()->id;
            $variable->save();

            $this->dispatch('pg:eventRefresh-variableTable');
            if($estado_prin == true){
                $this->dispatch('alertaInactivo');
            }else{
                $this->dispatch('alertaActivo');
            } 
        }
    }

    #[On('verVariable')]
    public function verVariable($data)
    { 
        $variable = Variable::find($data);
        $this->nombre = $variable->nombre;
        $this->tipo = $variable->tipo;

        $this->dispatch('modalVerVariable');

    }

    #[On('editarVariable')]
    public function editarVariable($data)
    {
        $this->id_variable = $data;
        $variable = Variable::find($data);
        $this->nombre = $variable->nombre;
        $this->tipo = $variable->tipo;
        $this->dispatch('modalEditarVariable');

    }

    #[On('modificarVaribleAsignada')] 
    public function modificarVaribleAsignada(){
         
        if(!empty($this->nombre) && !empty($this->tipo)){
            DB::beginTransaction();
            try{ 
                $existe = Variable::where('nombre', $this->nombre)
                ->where('tipo', $this->tipo) 
                ->where('id', '!=', $this->id_variable)
                ->exists();

                if ($existe) {
                    $this->dispatch('variable_duplicada');
                    DB::rollBack();
                    
                }else{
                    //dd($this->nombre, $this->tipo);
                    $variable = Variable::find($this->id_variable); 
                        
                    if ($variable) { 
                        $variable->nombre = $this->nombre; 
                        $variable->tipo = $this->tipo;
                        $variable->id_user_updated = auth()->user()->id;
                        $variable->save(); 
                        DB::commit(); 

                        $this->dispatch('pg:eventRefresh-variableTable'); 
                        $this->dispatch('modificadoVariable');
                    }
                }
    
            } catch (\Throwable $th) {  
                DB::rollBack();
                $this->reset('nombre', 'tipo');
                $this->dispatch('error');
            } 
        }else{
            $this->dispatch('faltaDatos');
        }
    }
}
