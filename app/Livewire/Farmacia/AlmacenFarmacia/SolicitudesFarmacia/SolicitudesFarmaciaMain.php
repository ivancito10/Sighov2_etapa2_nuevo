<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\SolicitudesFarmacia;

use App\Http\Controllers\Farmacia\PdfSolicitudUnidadesController;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Farmacia\Solicitud;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Services\ObtenerDatosPersonalSsu;

class SolicitudesFarmaciaMain extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante, $usuarioEntrega;
    public $cargoAprobado, $unidadAprobado, $cargoEntregado, $unidadEntregado, $cargoRechazante, $unidadRechazante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion;
    public $medicamento, $count;
    public $fechaCreado;
    public $fueAprobado = false, $fueEntregado = false, $fechaCreacion, $userEdita, $fechaEdita;
    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.solicitudes-farmacia.solicitudes-farmacia-main');
    }

    #[On('ver_solicitud')]
    public function ver_solicitud($data)
    {
        //DD('DENTRA VER');
        $this->reseteao();
        $this->idSolicitud = $data;
        $this->mostrarSolicitud($data);
        $this->dispatch('verSolicitud');
    }

    #[On('anularSolicitud')]
    public function anularSolicitud($data)
    {
        //DD('DENTRA ANULAR');
        $this->idSolicitud = $data;
        $this->dispatch('anular_solicitud');
    }

    public function mostrarSolicitud($id)
    {

        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud')
            ->find($id);
        $solicitud = Solicitud::find($id);
        $this->cod_solicitud = $solicitud->cod_solicitud . ' / ' .  Carbon::parse($solicitud->created_at)->year;
        $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
        $this->motivo = $solicitud->motivo;
        $this->fechaCreado = Carbon::parse($solicitud->created_at)->format('d/m/Y H:i:s');
        $descri = $solicitud->descripcion;
        if ($descri) {
            $this->descripcion = $solicitud->descripcion;
        } else {
            $this->descripcion = 'No cuenta con una descripción';
        }

        $this->estadoValidacion = $solicitud->estado_validacion;
        $this->motivo_anulacion = $solicitud->motivo_anulacion;
        $this->fecha_anulacion = Carbon::parse($solicitud->fecha_anulacion)->format('d/m/Y H:i:s');
        $this->usuarioSolicita = $solicitud->usuarioCreador->name;

        // dd($solicitud);
        if ($solicitud->id_usuario_anula) {

            $this->usuarioAnula = $solicitud->usuarioAnulaSolicitud->name;
            //dd('fsdf', $this->usuarioAnula);
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioAnulaSolicitud->id_persona);
            $this->cargoRechazante = $datos_entregante['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadRechazante = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        }
        $this->fecha_aprobacion = Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i:s');
        $this->fecha_entregado = Carbon::parse($solicitud->fecha_entregado)->format('d/m/Y H:i:s');
        if ($solicitud->id_usuario_aprueba) {
            $this->fueAprobado = true;
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
            $datos_aprobante = $this->obtenerDatosPersona($solicitud->usuarioApruebaSolicitud->id_persona);
            $this->cargoAprobado = $datos_aprobante['cargo_personal_ssu']['nombre'];
            $this->unidadAprobado = $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        }
        if ($solicitud->id_usuario_entrega) {
            $this->fueEntregado = true;
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioEntregaSolicitud->id_persona);
            $this->cargoEntregado = $datos_entregante['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadEntregado = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        }
        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

        // MEDICAMENTOS INGRESADOS
        $this->medicamento = MedicamentoSolicitud::where('id_solicitud', $id)
            ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
            ->get();

        // datos para seguimiento
        $this->fechaCreacion = $solicitud->created_at;
        $this->fechaEdita = $solicitud->updated_at;
        $this->userEdita = $solicitud->usuarioEditor?->name;
    }

    public function reseteao()
    {
        $this->reset('fueAprobado', 'fueEntregado');
    }
    public function obtenerDatosPersona($id_persona)
    {

        $obtenerDatosPers = new ObtenerDatosPersonalSsu();
        return $obtenerDatosPers->obtenerDatosPersonalSsu($id_persona);
    }
    #[On('guardar_anular_solicitud')]
    public function GuardarAnularSolicitud($motivo)
    {
        $solicitud = Solicitud::find($this->idSolicitud);
        if ($solicitud) {
            $solicitud->estado_validacion = 'RECHAZADO';
            $solicitud->motivo_anulacion = $motivo;
            $solicitud->id_usuario_anula = auth()->user()->id;
            $solicitud->save();
            $this->dispatch('pg:eventRefresh-SolicitudesFarmaciaTable');
        }
    }

    #[On('verPdf')]
    public function verPdf($data)
    {
        $this->idSolicitud = $data;
        $this->mostrarPdfSolicitud($data);
        //$this->dispatch('verPdfSolicitudModal');
    }

    public $pdf_vista;

    public function mostrarPdfSolicitud($rowId)
    {
        $url = route('farmacia.almacenfarmacia.solicitud-farmacia.pdf', ['modo' => 'ver', 'rowId' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");

        /*$pdfSolicitudUnidad = new PdfSolicitudUnidadesController();
        $pdfSolicitudUnidad->generarPDFSolicitudUnidades($rowId);
        try {
            if ($rowId) {
                $path = 'Farmacia/Farmacia/' .  $rowId  . '.pdf';

                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
            }
        } catch (\Throwable $th) {
            return null;
        }*/
    }
}
