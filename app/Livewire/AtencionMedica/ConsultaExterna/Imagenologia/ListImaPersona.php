<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Imagenologia;

use App\Models\ConsultaExterna\ResultadoSolicitudImagenologia;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

class ListImaPersona extends Component
{
    public $id_persona;

    public $mostrar_id;

    public $resultadosRxfotos = [];

    public $resultadoObservaciones;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.imagenologia.list-ima-persona');
    }

    #[On('enviar-id-persona-imagen')]
    public function recibirIdPersona($id)
    {
        $this->id_persona = $id;
    }

    #[On('abrir_modal_pdf_ima')]
    public function abrir_modal_pdf_ima($id_ima)
    {

        $id_imagen = $id_ima['id_ima'];

        $this->mostrarPdfImagenologia($id_imagen);
        $this->dispatch('modalPdfImagenologia');
    }

    public $pdf_vista_imagen;

    public function mostrarPdfImagenologia($id_ima)
    {
        if ($id_ima) {
            $path = 'ConsultaExterna/Planes/Imagenologia/' . 'orden_imagenologia_' . $id_ima . '.pdf';

            $fileContents = Storage::disk('ftp')->get($path);
            $pdfBase64 = base64_encode($fileContents);
            $this->pdf_vista_imagen = $pdfBase64;
        }
    }

    #[On('abrirModalResultadosPlacasRX')]
    public function abrirModalResultadosPlacasRX($rowId)
    {
        $this->resultadosRxfotos = ResultadoSolicitudImagenologia::where('id_solicitud', $rowId)
            ->get()
            ->map(function ($foto) {

                $path = 'ConsultaExterna/Imagenologia/Rx/' . $foto->nombre_placa;

                try {
                    $fileContents = Storage::disk('ftp')->get($path);
                    $foto->imagen_base64 = base64_encode($fileContents);
                } catch (\Throwable $th) {
                    $foto->imagen_base64 = null;
                }

                return $foto;
            });
        // foreach ($this->resultadosRxfotos as $row) {
        //     dd($row->imagen_base64);
        // }
        // dd($this->resultadoObservaciones);
        if (count($this->resultadosRxfotos) == 0) {
            $this->dispatch('sin_resultados_placas_rx');

            return;
        } else {
            $this->resultadoObservaciones = $this->resultadosRxfotos->first()->observaciones;

            $this->dispatch('abrirModalResultadosPlacasRXImagenes');
        }
    }
}
