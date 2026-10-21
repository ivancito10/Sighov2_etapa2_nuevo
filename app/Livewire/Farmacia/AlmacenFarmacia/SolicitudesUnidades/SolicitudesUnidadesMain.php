<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\SolicitudesUnidades;

use App\Http\Controllers\Farmacia\PdfSolicitudUnidadesController;
use Livewire\Attributes\On;
use Livewire\Component;

use Illuminate\Support\Facades\Storage;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerDatosPersonalSsu;

class SolicitudesUnidadesMain extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante;
    public $cargoAprobado, $unidadAprobado, $cargoEntregado, $unidadEntregado, $cargoRechazante, $unidadRechazante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion, $usuarioEntrega;
    public $medicamento, $count;
    public $fechaCreado;
    public $fueAprobado = false, $fueEntregado = false, $fechaCreacion, $fechaEdita, $userEdita;
    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.solicitudes-unidades.solicitudes-unidades-main');
    }

    #[On('ver_solicitud')]
    public function ver_solicitud($data)
    {
        $this->resetearVariables();
        $this->idSolicitud = $data;
        $this->mostrarSolicitud($data);
        $this->dispatch('verSolicitud');
    }

    public function obtenerDatosPersona($id_persona)
    {
        $obtenerDatosPers = new ObtenerDatosPersonalSsu();
        return $obtenerDatosPers->obtenerDatosPersonalSsu($id_persona);
    }

    public function mostrarSolicitud($id)
    {

        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud', 'usuarioEntregaSolicitud')
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

        if ($solicitud->id_usuario_anula) {
            $this->usuarioAnula = $solicitud->usuarioAnulaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioAnulaSolicitud->id_persona);
            $this->cargoRechazante = $datos_entregante['cargo_personal_ssu']['nombre'];
            $this->unidadRechazante = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioAnula = null;
        }

        $this->fecha_aprobacion = Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i:s');
        $this->fecha_entregado = Carbon::parse($solicitud->fecha_entregado)->format('d/m/Y H:i:s');
        if ($solicitud->id_usuario_aprueba) {
            $this->fueAprobado = true;
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
            $datos_aprobante = $this->obtenerDatosPersona($solicitud->usuarioApruebaSolicitud->id_persona);
            $this->cargoAprobado = $datos_aprobante['cargo_personal_ssu']['nombre'];
            $this->unidadAprobado = $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioAprueba = null;
        }

        if ($solicitud->id_usuario_entrega) {
            $this->fueEntregado = true;
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioEntregaSolicitud->id_persona);
            $this->cargoEntregado = $datos_entregante['cargo_personal_ssu']['nombre'];
            $this->unidadEntregado = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioEntrega = null;
        }

        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'N/A';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'N/A';

        // MEDICAMENTOS INGRESADOS
        $this->medicamento = MedicamentoSolicitud::where('id_solicitud', $id)
            ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
            ->get();
        // segiuomiento
        $this->fechaCreacion = $solicitud->created_at;
        $this->fechaEdita = $solicitud->updated_at;
        $this->userEdita = $solicitud->usuarioEditor?->name;
    }
    public function resetearVariables()
    {
        $this->reset('fueAprobado', 'fueEntregado');
    }

    #[On('anularSolicitud')]
    public function anularSolicitud($data)
    {
        $this->idSolicitud = $data;
        $this->dispatch('anular_solicitud');
    }

    #[On('verPdf')]
    public function verPdf($data)
    {
        $this->idSolicitud = $data;
        $this->mostrarPdfSolicitud($data);
        //$this->dispatch('verPdfSolicitud');
    }

    public $pdf_vista;
    public function mostrarPdfSolicitud($rowId)
    {
        $url = route('farmacia.almacenfarmacia.solicitudes-unidades.pdf', ['modo' => 'ver', 'rowId' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");

        /*try {
            if ($id) {
                $path = 'Farmacia/Farmacia/' .  $id  . '.pdf';

                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
            }
        } catch (\Throwable $th) {
            return null;
        }*/
    }

    #[On('guardar_anular_solicitud')]
    public function guardar_anular_solicitud($data)
    {
        DB::beginTransaction();
        try {
            $solicitudAnulada = Solicitud::find($this->idSolicitud);
            $solicitudAnulada->estado_validacion = 'RECHAZADO';
            $solicitudAnulada->fecha_anulacion = Carbon::now();
            $solicitudAnulada->id_usuario_anula = auth()->user()->id;
            $solicitudAnulada->motivo_anulacion = $data;
            $solicitudAnulada->save();
            DB::commit();
            $this->dispatch('pg:eventRefresh-AlmacenFarmaciaTable');
            $this->dispatch('solicitudAnulada');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('errorAnulacion');
        }
    }
}
