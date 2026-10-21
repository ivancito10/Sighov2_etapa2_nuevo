<?php

namespace App\Livewire\RayosX;
use App\Services\ServicioPersona;
use Carbon\Carbon;
use Livewire\Component;
use App\Services\DatosEspecialista;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\RayosX\PlacasRayosX;
use Laravel\Reverb\Loggers\Log;
use Livewire\Attributes\Validate;

class RayosXRegister extends Component
{
    public $id_persona;
    public $nombre_asegurado, $ci, $genero,$institucion, $tipo_asegurado,$matricula_asegurado, $fecha_nacimiento, $edad;
    #[Validate]
    public $tipo = false, $nro_placas, $estudio_origen, $nombre_solicitante,$estudios,$observacion;
    #[Validate]
    public $fecha_estudio;
   public $ingresoManual = false, $today;
    public $solicitantes = [], $solicitante;

    public function render()
    {
        return view('livewire.rayos-x.rayos-x-register');
    }

    public function mount(){
        $this->mostrarDatosAsegurado($this->id_persona);
        $this->listadoEspecialistas();
        $this->fecha_estudio = now()->format('Y-m-d');
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersonales =  new ServicioPersona();
        return $datosPersonales->datosPersona($id_persona);
    }

    public function listadoEspecialistas(){
        $datosEspecialistas = new DatosEspecialista();
        $especialistas = $datosEspecialistas->listaEspecialistas();
       $this->solicitantes = array_map(function($especialista){
        $persona = $especialista['persona_especialista'];
        return (object)[
            'id' => $persona['id'],
            'nombre_completo' => trim($persona['nombres'] . ' ' . $persona['p_apellido'] . ' ' . $persona['s_apellido']),
        ];
       }, $especialistas);
    }

    public function updatedEstudioOrigen(){
        if($this->estudio_origen == 'EXTERNO'){
            $this->ingresoManual = true;
            $this->nombre_solicitante = null;
        }else{
            $this->ingresoManual = false;
            $this->nombre_solicitante = null;
        }  
    }

    public function mostrarDatosAsegurado($id_persona){
        $asegurado = $this->obtenerDatosPersona($id_persona);
        if($asegurado){
            $this->nombre_asegurado = $asegurado['p_apellido'] .' '.$asegurado['s_apellido'].' '. $asegurado['nombres'];
            if($asegurado['complemento']){
                $this->ci = $asegurado['nombres']. '-'. $asegurado['ci'];
            }else{
                $this->ci = $asegurado['ci'];
            }
            $this->matricula_asegurado = $asegurado['matricula_seguro'];
            $this->tipo_asegurado = $asegurado['tipo_asegurado'];
            $this->institucion = $asegurado['institucion'];

            if ($asegurado['sexo'] == 'F') {
                $this->genero = 'FEMENINO';
            } elseif ($asegurado['sexo'] == 'M') {
                $this->genero = 'MASCULINO';
            } else {
                $this->genero = ' SIN DATO';
            }
            $this->edad = Carbon::parse($asegurado['fecha_nacimiento'])->age;
            $this->fecha_nacimiento = Carbon::parse($asegurado['fecha_nacimiento'])->Format('d/m/Y ');
        }
    }

    public function rules()
    {
        return [
            'nro_placas' => 'required',
            'fecha_estudio' => 'required|date|before_or_equal:today',
            'estudio_origen'  => 'required',
            'nombre_solicitante'  => 'required',
            'estudios'  => 'required',
            'observacion'  => 'required',

        ];
    }
    
    public function obtenerDatosSolicitante(){
        if ($this->ingresoManual) {
            return (object) [
                'id' => null,
                'nombre_completo' => $this->nombre_solicitante,
            ];
        }
        $solicitante = collect($this->solicitantes)->firstWhere('id', $this->nombre_solicitante);

        return (object) [
            'id' => $solicitante->id,
            'nombre_completo' => $solicitante->nombre_completo ?? '',
        ];
    }
    
    #[On('crearRayosX')]
    public function crearRayosX(){
      $this->validate();
        try {
            DB::beginTransaction();
            $solicitante= $this->obtenerDatosSolicitante();
            $rayos_x_nuevo = PlacasRayosX::create([
                'estado' => true,
                'tipo' => $this->tipo,
                'nro_placas' => $this->nro_placas,
                'fecha_estudio' =>$this->fecha_estudio,
                'id_persona_asegurado' => $this->id_persona,
                'id_persona_solicitante' => $solicitante->id,
                'nombre_solicitante' => $solicitante->nombre_completo,
                'observacion' => $this->observacion,
                'estudios' =>$this->estudios,
                'estudio_origen' => $this->estudio_origen,       
                'id_user_created' => auth()->id(),
            ]);
            DB::commit();
            $this->dispatch('rayosXExitoso');
            return redirect()->route('rayos_x.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th->getMessage());
            $this->dispatch('errorNuevo');
            Log::error('Error al guardar rayos_x: ' . $th->getMessage());
        }
    }


    public function atras(){
       return redirect()->route('rayos_x.index');
    }
    public function cacelarRegistroRayosX(){
        $this->dispatch('rayosXCancelado');
        return redirect()->route('rayos_x.index');
        
     }
}
