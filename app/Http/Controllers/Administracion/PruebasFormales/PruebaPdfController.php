<?php

namespace App\Http\Controllers\Administracion\PruebasFormales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PruebaPdfController extends Controller
{
    /**
     * Genera la vista previa del PDF con el posicionamiento dinámico.
     */
    public function verPdfTest(Request $request)
    {
        // 1. Capturamos las coordenadas 'top' y 'left' enviadas desde Livewire por la URL.
        // Si por alguna razón no se envían, tomamos 15mm por defecto.
        $marginTop = $request->query('top', 15);
        $marginLeft = $request->query('left', 15);

        // Variables simuladas que requiere tu vista de prueba
        $nombre_especialista = auth()->user()->name ?? "Dr. Eleazar Cruz";

        // 2. Cargamos la vista de prueba que creamos previamente pasándole los mm
        // resources\views\Administracion\PruebasFormalesEjecucionCodigo\ConsultaExternaTest.blade.php
        $pdf = Pdf::loadView('Administracion.PruebasFormalesEjecucionCodigo.PdfConsultaExternaTest', compact(
            'marginTop',
            'marginLeft',
            'nombre_especialista'
        ))->setPaper('letter', 'portrait');

        // 3. Retornamos el stream inline para abrirlo en la pestaña del navegador de forma interactiva
        return $pdf->stream('PdfConsultaExternaTest.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }
}