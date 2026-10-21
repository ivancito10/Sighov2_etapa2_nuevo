<?php

namespace App\Livewire\Farmacia\Farmacia\Receta;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use App\Models\Administracion\Persona;
use App\Models\Administracion\LinkServicio;
use App\Livewire\Farmacia\Farmacia\Receta\RecetaMedicamentoTable;
use App\Models\ConsultaExterna\RecetaMedicamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Reverb\Loggers\Log; 

class HabilitarMedRegister extends Component
{

    public $id_persona;
    public $id_receta_medicamento;
    public function render()
    {
        return view('livewire.farmacia.farmacia.receta.habilitar-med-register');
    }

    public function mount(){
        $this->dispatch('DatosPersona', id_persona : $this->id_persona)->to(RecetaMedicamentoTable::class);
        $this->mostrarDatosPersona($this->id_persona);
    }

    public $id_per, $edad, $institucion, $foto_personal, $alergias, $instituciones, $nro_instituciones,
    $codigo_institucional, $persona, $persona_institucion;
    public function mostrarDatosPersona($id_persona){
        //dd($agendaData);
        
        $this->id_per = $id_persona;
        $this->persona = Persona::where('id', $id_persona)->first();

        $fechaNacimiento = $this->persona?->fecha_nacimiento ? \Carbon\Carbon::parse($this->persona->fecha_nacimiento) : null;
        $this->edad = $fechaNacimiento?->age;  
        
        // Servicio para mostrar datos tipo asegurado e institucion del asegurado
        $dato_per = new ServicioPersona();
        $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
        $this->institucion = $datos_persona['institucion'] ?? null;
        $this->alergias = $datos_persona['alergias'];
        $this->foto_personal = $datos_persona['url_foto'];
         

        $dato_inst = new ServicioPersonaInstitucion();
        $this->persona_institucion = $dato_inst->personaInstitucion($id_persona); 
          

        // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
              
 
            // Asignar array vacío si no viene 'instituciones'
            $this->instituciones = $persona_institucion['instituciones'] ?? []; 
            $this->nro_instituciones = count($this->instituciones);

            if (!empty($this->instituciones)) { 
                foreach ($this->instituciones as $institucion_individual) { 
                    if ($institucion_individual['nombre_institucion'] == $this->institucion) { 
                        $this->codigo_institucional = $institucion_individual['codigo_institucional'] ?? null; 
                        break; 
                    }
                }
            }else{
                $this->codigo_institucional = null;
            }
            //dd($this->codigo_institucional);
            
 
    }
     
    public function atrasInicio()
    {   //enviar a ruta que ya se atendio
        return redirect()->route('farmacia.receta.index');
    }

    #[On('registroHabilitar')]
    public function registroHabilitar($data){
        $this->id_receta_medicamento = $data;
        $this->dispatch('funcionAlertaMotivo');
    }

    #[On('medicamentoHabilitado')]
    public function medicamentoHabilitado($motivo){
        try{
            $datosRecMed = RecetaMedicamento::find($this->id_receta_medicamento);
            $datosRecMed->estado_validez = false;
            $datosRecMed->id_user_habilita = auth()->user()->id;
            $datosRecMed->motivo_habilita = $motivo;
            $datosRecMed->save();
            $this->dispatch('medicamentoHabilitado2');
            $this->dispatch('pg:eventRefresh-RecetaMedicamentoTable');
        }catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            // Registrar el error con mensaje y contexto
            Log::error('Error al anular receta', [
                'message' => $th->getMessage(),
                'archivo'    => $th->getFile(),
                'linea'    => $th->getLine(),
                'trace'   => $th->getTraceAsString(),
            ]);
            // Disparar evento o notificación
            $this->dispatch('recetaNoAnulada');
        }
        
    }

     

}
