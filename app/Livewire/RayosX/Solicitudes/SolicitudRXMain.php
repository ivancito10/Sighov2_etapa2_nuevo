<?php

namespace App\Livewire\RayosX\Solicitudes;

use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\ResultadoSolicitudImagenologia;
use App\Services\DatosEspecialista;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

class SolicitudRXMain extends Component
{
    public $id_solicitud;

    public $espacialista;

    public $especialidad;

    public $fechaAtencion;

    public $diagnosticoDescriptivo;

    public $indicaciones;

    public $nroSolicitud;

    public $tipoEstudio;

    public $resultado;

    public $userCrea;

    public $fechaCreado;

    public $userEdit;

    public $fechaEditado;

    public $imagenesResultado;

    public $imahenesResultado;

    public $idImagenResultados;

    public $resultadosRxfotos;

    public function render()
    {
        return view('livewire.rayos-x.solicitudes.solicitud-r-x-main');
    }

    #[On('abrirModalRegistroPlacasRX')]
    public function abrirModalCrearSolicitudRX($data)
    {
        // dd($data['id']);
        $this->id_solicitud = $data['id'];
        $this->dispatch('abrirModalCrearSolicitudRX');
        $this->dispatch('cargarSolicitudRX', id_solicitud: $this->id_solicitud)
            ->to('rayos-x.solicitudes.solicitudes-register');
    }

    #[On('verDatosRX')]
    public function verDatosRX($data)
    {
        $datos_solicitud = $data['id'];
        $planes = HCPlanes::where('id_plan', 3)
            ->where('id_corresponde', $datos_solicitud)
            ->with('HistoriaClinica', 'HistoriaClinica.usuarioCreador', 'HistoriaClinica.usuarioEditor')
            ->first();
        $id_especialista = $planes->HistoriaClinica->id_especialista;
        // dd($datos_solicitud,$planes, $planes->HistoriaClinica, $data);

        $dato_esp = new DatosEspecialista();
        $datos = $dato_esp->datosEspecialista($id_especialista);
        $this->espacialista = $datos['persona_especialista']['nombres'].' '.$datos['persona_especialista']['p_apellido'].' '.$datos['persona_especialista']['s_apellido'];
        $this->especialidad = $datos['especialidad_especialista']['especialidad'];
        // dd($this->espacialista);
        $this->fechaAtencion = $planes->HistoriaClinica->fecha_hora;
        $this->diagnosticoDescriptivo = $planes->HistoriaClinica->descriptivo;
        $this->indicaciones = $data['indicaciones'];
        $this->nroSolicitud = $data['nro_solicitud'];

        $this->tipoEstudio = $data['tipo_imagenologia']['nombre'];
        $this->resultado = $data['resultado'];
        $this->userCrea = $planes->HistoriaClinica->usuarioCreador->name;
        $this->fechaCreado = $planes->HistoriaClinica->created_at;
        $this->userEdit = $planes->HistoriaClinica->usuarioEditor->name ?? '';

        $this->fechaEditado = $planes->HistoriaClinica->updated_at ? $planes->HistoriaClinica->updated_at : '';
        $this->dispatch('abrirModalVerSolicitudRX');
        $this->idImagenResultados = $data['id'];
        $this->resultadosRxfotos = ResultadoSolicitudImagenologia::where('id_solicitud', $this->idImagenResultados)
            ->get();

        $this->imagenesResultado = [];
        foreach ($this->resultadosRxfotos as $row) {
            $fotoRX = $this->obtenerImagenesRX($row->nombre_placa);
            $this->imagenesResultado[] = [
                'id' => $row->id,
                'observaciones' => $row->observaciones,
                'nombre_placa' => $row->nombre_placa,
                'fotoRX' => $fotoRX,
            ];
        }
        // dd($this->imagenesResultado);
        // dd($this->imagenesResultado);
        // dd($this->imagenesResultado);

    }

    private function obtenerImagenesRX($filename)
    {
        // dd($filename);
        try {
            if ($filename) {
                $path = 'ConsultaExterna/Imagenologia/Rx/'.$filename;
                $imageData = Storage::disk('ftp')->get($path);
                $fotoRX = base64_encode($imageData);

                return $fotoRX;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
