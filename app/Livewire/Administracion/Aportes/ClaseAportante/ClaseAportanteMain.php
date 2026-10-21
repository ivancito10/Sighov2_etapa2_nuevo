<?php

namespace App\Livewire\Administracion\Aportes\ClaseAportante;

use App\Services\VerificacionDatosPersonaAsussIOV2;
use Livewire\Component;

class ClaseAportanteMain extends Component
{
    public function render()
    {
        return view('livewire.Administracion.aportes.clase-aportante.clase-aportante-main');
    }

    public function mount(){
        /*$verificarPersona = new VerificacionDatosPersonaAsussIOV2();
        $datosPersona = [
            'ci' => "9868420",
            'complemento' => null,
            'tipoPersona' => "1",
            'nombres' => "BORIS ORLANDO",
            'apellidoPaterno' => "ROQUE",
            'apellidoMaterno' => "HUANCA",
            'fechaNacimiento' => "29/04/2000" 
        ];
        //dd($verificarPersona->verificarPersonaSegip($datosPersona));
        dd($verificarPersona->probandoCachePoblacion());*/
    }
}
