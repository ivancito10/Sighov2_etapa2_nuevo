<?php

namespace App\Livewire\RayosX;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\ServicioPersona;
use App\Models\RayosX\PlacasRayosX;
use Carbon\Carbon;

class RayosXMain extends Component
{
   
    public $nombre_asegurado, $ci, $genero,$institucion, $tipo_asegurado,$matricula_asegurado, $fecha_nacimiento, $edad, $id_rayos_x;
    public $tipo = false, $nro_placas, $estudio_origen, $nombre_solicitante,$estudios,$observacion;
    public $fecha_estudio;
    

    public function render()
    {
        return view('livewire.rayos-x.rayos-x-main');
    }

    #[On('abrirModalVer')]
    public function abrirModalVer($data){
        $this->dispatch('modalVerRayosX');
        $this->mostrarDatos($data);
    }
    public function obtenerDatosPersona($id_persona)
    {
        $datosPersonales =  new ServicioPersona();
        return $datosPersonales->datosPersona($id_persona);
       
    }

    public function mostrarDatos($data){
        if($data){
            $this->estado = $data['estado'];
            $this->nro_placas = $data['nro_placas'];
            $this->fecha_estudio = $data['fecha_estudio'];
            $this->estudio_origen = $data['estudio_origen'];
            $this->nombre_solicitante = $data['nombre_solicitante'];
            $this->estudios = $data['estudios'];
            $this->observacion = $data['observacion'];
            $this->tipo = $data['tipo'];
            $datosAsegurado = $this->obtenerDatosPersona($data['id_persona_asegurado']);
    
            if ($datosAsegurado) {
                $this->nombre_asegurado = $datosAsegurado['p_apellido'] . ' ' . $datosAsegurado['s_apellido'] . ' ' . $datosAsegurado['nombres'];
                $this->tipo_asegurado = $datosAsegurado['tipo_asegurado'];
    
                if ($datosAsegurado['complemento']) {
                    $this->ci = $datosAsegurado['ci'] . ' - ' . $datosAsegurado['complemento'];
                } else {
                    $this->ci = $datosAsegurado['ci'];
                }
    
                $this->matricula_asegurado = $datosAsegurado['matricula_seguro'];
                $this->genero = $datosAsegurado['sexo'];
                $this->institucion = $datosAsegurado['institucion'];
                $this->edad = Carbon::parse($datosAsegurado['fecha_nacimiento'])->age;
            }
        }
    }

    #[On('cambiarEstadoSwitch')]
    public function cambiarEstadoSwitch($dato){
        $placaRayosX = PlacasRayosX::find($dato);
        $this->id_rayos_x = $dato;
        if($placaRayosX){
            $this->tipo = $placaRayosX->tipo;
        }
        $this->dispatch('cambiarTipoRayosX');
    }
    #[On('actualizarTipo')]
    public function actualizarTipo($confirmado = null)
    {
        if ($confirmado) {
            $placaRayosX = PlacasRayosX::find($this->id_rayos_x );
            if ($placaRayosX) {
                $tipoActual = $placaRayosX->tipo;
                $placaRayosX->tipo = !$tipoActual;
                $placaRayosX->save();
                $this->dispatch('pg:eventRefresh-RayosXTable');
            }
        }
    }
    
}
