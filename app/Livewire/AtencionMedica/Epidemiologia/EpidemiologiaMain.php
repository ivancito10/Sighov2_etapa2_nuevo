<?php

namespace App\Livewire\AtencionMedica\Epidemiologia;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class EpidemiologiaMain extends Component
{
    //public $index = 0;
    public $archivos = [];

    public function render()
    {
        return view('livewire.atencion-medica.epidemiologia.epidemiologia-main');
    }

    public function mount()
    {
        $this->archivos = $this->lecturaFichas();
        // dd($this->archivos);
    }

    public function lecturaFichas()
    {
        $ruta = public_path('docs/ConsultaExterna/FichasEpidemiologicas');
        if (! File::exists($ruta)) {
            // dd('no hay waaaaaa');
            return [];
        }

        return collect(File::files($ruta))->map(function ($archivo) {
            return [
                'nombre' => strtoupper(pathinfo($archivo->getFilename(), PATHINFO_FILENAME)),
                'archivo' => $archivo->getFilename(),
            ];
        })->sortBy('nombre')->values()->toArray();
    }
}
