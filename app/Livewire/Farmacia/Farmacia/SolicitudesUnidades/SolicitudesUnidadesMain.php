<?php

namespace App\Livewire\Farmacia\Farmacia\SolicitudesUnidades;

use App\Http\Controllers\Farmacia\PdfSolicitudUnidadesController;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Farmacia\MedicamentoSolicitud;
use Illuminate\Support\Facades\Storage;
use App\Models\Farmacia\Solicitud;
use App\Services\ObtenerDatosPersonalSsu;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\ObtenerDatosUnidadPorId;

use Carbon\Carbon;


class SolicitudesUnidadesMain extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante, $usuarioEntrega;
    public $cargoAprobado, $unidadAprobado, $cargoEntregado, $unidadEntregado, $cargoRechazante, $unidadRechazante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion;
    public $medicamento, $count;
    public $fechaCreado, $fueAprobado = false, $fueEntregado = false, $userEdita, $fechaEdita, $fechaCreacion, $unidad_servicio;
    public function render()
    {
        return view('livewire.farmacia.farmacia.solicitudes-unidades.solicitudes-unidades-main');
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
        $datosPersonal = new ObtenerDatosPersonalSsu();
        return $datosPersonal->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerDatosUnidad($id_unidad)
    {
        $obtenerDatosUnidad = new ObtenerDatosUnidadPorId();
        return $obtenerDatosUnidad->obtenerDatosUnidadPorId($id_unidad);
    }


    public function mostrarSolicitud($id)
    {

        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud', 'usuarioEntregaSolicitud')
            ->find($id);
        $solicitud = Solicitud::find($id);
        $this->cod_solicitud = $solicitud->cod_solicitud . ' / ' .  Carbon::parse($solicitud->created_at)->year;
        $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
        $this->motivo = $solicitud->motivo;
        $unidades_servicio = $this->obtenerDatosUnidad($solicitud->id_unidad_servicio);
        //dd($unidades_servicio);
        $this->unidad_servicio = $unidades_servicio['nombre'] ?? 'SIN DATO';

        $this->fechaCreado = Carbon::parse($solicitud->created_at)->format('d/m/Y H:i:s');
        if ($solicitud->descripcion) {
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
            $this->cargoRechazante = $datos_entregante['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadRechazante = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        } else {
            $this->usuarioAnula = null;
        }
        $this->fecha_aprobacion = Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i:s');
        $this->fecha_entregado = Carbon::parse($solicitud->fecha_entregado)->format('d/m/Y H:i:s');
        if ($solicitud->id_usuario_aprueba) {
            $this->fueAprobado = true;
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
            // dd($solicitud->usuarioApruebaSolicitud->id_persona," este es el que aprueba dice");
            $datos_aprobante = $this->obtenerDatosPersona($solicitud->usuarioApruebaSolicitud->id_persona);
            $this->cargoAprobado = $datos_aprobante['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadAprobado = $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
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
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

        // MEDICAMENTOS INGRESADOS
        $this->medicamento = MedicamentoSolicitud::where('id_solicitud', $id)
            ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
            ->get();
        // dd($this->medicamento);
        // datos para seguimiento
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

    #[On('verPdfSolicitud')]
    public function verPdfSolicitud($data)
    {

        $this->idSolicitud = $data;
        $this->mostrarPdfSolicitud($data);
        //$this->dispatch('verPdfSolicitudModal');
    }

    public $pdf_vista;

    public function mostrarPdfSolicitud($rowId)
    {
        $url = route('farmacia.farmacia.solicitud-unidad.pdf', ['modo' => 'ver', 'id' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");
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
            $this->dispatch('pg:eventRefresh-SolicitudesUnidadesTable');
            $this->dispatch('solicitudAnulada');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('errorAnulacion');
        }
    }
}
