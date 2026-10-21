<?php

namespace App\Livewire\Administracion\PruebasFormalesEjecucionCodigo;

use Livewire\Component;

class PdfPosicion extends Component
{
    // Por defecto inicia en la parte superior
    public $marginTop = 15;
    public $marginLeft = 15; // El margen izquierdo se mantiene fijo y seguro
    
    public $sectorActivo = 'superior';

    // Simplificado a solo dos regiones físicas reales (en milímetros)
    protected $sectores = [
        'superior' => ['top' => 15,  'left' => 15],
        'inferior' => ['top' => 140, 'left' => 15], // 140mm es justo la mitad de una hoja Carta
    ];

    public function seleccionarSector($nombreSector)
    {
        if (array_key_exists($nombreSector, $this->sectores)) {
            $this->sectorActivo = $nombreSector;
            $this->marginTop = $this->sectores[$nombreSector]['top'];
            $this->marginLeft = $this->sectores[$nombreSector]['left'];
        }
    }

    public function exportarPdf()
    {
        // Concatenación corregida con el '&' correcto para los parámetros query
        $this->js('window.open("/administracion/pruebas-formales/ver-pdf?top=' . $this->marginTop . '&left=' . $this->marginLeft . '", "_blank")');
    }

    public function render()
    {
        return view('livewire.Administracion.pruebas-formales-ejecucion-codigo.pdf-posicion');
    }
}