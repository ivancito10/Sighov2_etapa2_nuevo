<?php

namespace App\Livewire\Farmacia\Farmacia\SolicitudesAlmacen;

use App\Http\Controllers\Farmacia\Farmacia\PdfSolicitudController;
use App\Http\Controllers\Farmacia\PdfSolicitudUnidadesController;
use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use App\Models\Farmacia\Solicitud;
use App\Models\Farmacia\MedicamentoSolicitud;
use Illuminate\Support\Facades\Http;
use App\Services\ObtenerDatosPersonalSsu;
use App\Models\Farmacia\MedicamentoStock;
use Illuminate\Support\Facades\Storage;
use App\Services\ListaUnidades;

class SolicitudesAlmacenMain extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion, $usuarioEntrega, $unidadRechazante, $cargoRechazante;
    public $cargoEntregado, $unidadEntregado;
    public $medicamento, $count;
    public $fechaCreado;
    public $stockAlmacen, $stockFarmacia;
    public $fueAprobado, $fueEntregado;

    public function render()
    {
        return view('livewire.farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-main');
    }


    public function redirigirRegistrarSolicitudAlmancen()
    {
        return redirect()->route('farmacia.solicitudes.almacen.create');
    }

    #[On('verSolicitudAlmacen')]
    public function verSolicitudAlmacen($data)
    {
        $this->idSolicitud = $data;
        $this->mostrarSolicitud($this->idSolicitud);
        $this->dispatch('verSolicitudAlmacenModal');
    }

    public function obtenerDatosPersona($id_persona)
    {
        $obtenerDatosPers = new ObtenerDatosPersonalSsu();
        return $obtenerDatosPers->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        //  dd(collect($datosUnidades->listaUnidades()));
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    public function mostrarSolicitud($id)
    {
        $solicitud = Solicitud::with(
            'usuarioApruebaSolicitud',
            'usuarioCreador',
            'usuarioApruebaSolicitud',
            'usuarioAnulaSolicitud',
            'usuarioEntregaSolicitud'
        )
            ->find($id);

        $this->cod_solicitud = 'SOL/ALM/' . $solicitud->cod_solicitud . '/' .  Carbon::parse($solicitud->created_at)->year;
        $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
        $this->fechaCreado = Carbon::parse($solicitud->created_at)->format('d/m/Y');

        $this->descripcion = $solicitud->descripcion;

        // datos del regitras
        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        $this->usuarioSolicita = $solicitud->usuarioCreador->name;
        $this->estadoValidacion = $solicitud->estado_validacion;

        //si fue anulado 
        $this->motivo_anulacion = $solicitud->motivo_anulacion;
        $this->fecha_anulacion = Carbon::parse($solicitud->fecha_anulacion)->format('d/m/Y');
        if ($solicitud->id_usuario_anula) {
            $this->usuarioAnula = $solicitud->usuarioAnulaSolicitud->name;
            $datos_anula = $this->obtenerDatosPersona($solicitud->usuarioAnulaSolicitud->id_persona);
            $this->cargoRechazante = $datos_anula['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadRechazante = $datos_anula['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        } else {
            $this->usuarioAnula = null;
        }
        // ENTREGASDO
        if ($solicitud->id_usuario_entrega) {
            $this->fueEntregado = true;
            $this->fecha_entregado = Carbon::parse($solicitud->fecha_entregado)->format('d/m/Y');
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;

            $datos_entrega = $this->obtenerDatosPersona($solicitud->usuarioEntregaSolicitud->id_persona);
            $this->cargoEntregado = $datos_entrega['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadEntregado = $datos_entrega['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        }





        // MEDICAMENTOS INGRESADOS
        $medicamentos = MedicamentoSolicitud::where('id_solicitud', $id)
            ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
            ->get();

        $this->medicamento = [];
        foreach ($medicamentos as $med) {
            if (isset($med->id_medicamento)) {
                $stockAlmacen = MedicamentoStock::where('id_medicamento', $med->id_medicamento)
                    ->where('id_unidad', $this->obtenerIdAlmacen())
                    ->sum('stock_actualizado');

                $stockFarmacia = MedicamentoStock::where('id_medicamento', $med->id_medicamento)
                    ->where('id_unidad', $this->obtenerIdFarmacia())
                    ->sum('stock_actualizado');

                $this->medicamento[] = [
                    'id_medicamento' => $med->id_medicamento,
                    'codigo_medicamento' => $med->medicamentoMedicamentoSolicitud->codigo_medicamento ?? 'N/A',
                    'nombre' => $med->medicamentoMedicamentoSolicitud->nombre ?? 'N/A',
                    'forma_farmaceutica' => $med->medicamentoMedicamentoSolicitud->forma_farmaceutica ?? 'N/A',
                    'concentracion' => $med->medicamentoMedicamentoSolicitud->concentracion ?? 'N/A',
                    'stockFarmacia' => $stockFarmacia,
                    'stockAlmacen' => $stockAlmacen,
                ];
            } else {
            }
        }
    }

    #[On('editarSolicitudAlmacen')]
    public function editarSolicitudAlmacen($data)
    {
        $this->dispatch('verSolicitudAlmacenModal');
    }

    #[On('deshabilitarSolicitudAlmacen')]
    public function deshabilitarSolicitudAlmacen($data)
    {
        $this->dispatch('verSolicitudAlmacenModal');
    }

    #[On('anular_solicitud')]
    public function anular_solicitud($data)
    {
        $this->idSolicitud = $data;
        $this->dispatch('alerta_anular_solicitud');
    }

    #[On('guardar_anular_solicitud')]
    public function guardar_anular_solicitud($data)
    {

        try {
            $solicitud = Solicitud::find($this->idSolicitud);
            if ($solicitud) {
                $solicitud->estado_validacion = 'RECHAZADO';
                $solicitud->motivo_anulacion = $data;
                $solicitud->fecha_anulacion = Carbon::now();
                $solicitud->id_usuario_anula = auth()->user()->id;
                $solicitud->save();

                $this->dispatch('pg:eventRefresh-SolFarmaciaAlmacenFarmaciaTable');
                $this->dispatch('rechazo_guardado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('errorFuncion');
        }
    }

    #[On('verPdfSolicitud')]
    public function verPdfSolicitud($data)
    {
        $this->idSolicitud = $data;
        $this->mostrarPdfSolicitud($data);
        //$this->dispatch('verPdfSolicitudAlmacenModal');
    }

    public $pdf_vista;

    public function mostrarPdfSolicitud($rowId)
    {
        $url = route('farmacia.farmacia.solicitudes-almacen.pdf', ['modo' => 'ver', 'id' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");

        /*try {
            $fileName = "SOLICITUD_ALMACEN_{$rowId}.pdf";
            $path = "/Farmacia/Farmacia/Solicitud_Almacen/{$fileName}";

            if (Storage::disk('ftp')->exists($path)) {
                
                $fileContents = Storage::disk('ftp')->get($path);
                                $pdfBase64 = 'data:application/pdf;base64,' .base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
                return;
            }

            $url = route('farmacia.farmacia.solicitudes-almacen.pdf', ['id' => $rowId]);
           $this->js("window.open('{$url}', '_blank');");

        } catch (\Throwable $th) {
            return null;
        }

        $pdfSolicitudAlmacen = new PdfSolicitudController();
        $pdfSolicitudAlmacen->generarPDFSolicitudAlmacen($rowId);
        try {
            if ($rowId) {
                $fileName = "SOLICITUD_ALMACEN_{$rowId}.pdf";
                $path = "/Farmacia/Farmacia/Solicitud_Almacen/{$fileName}";


                $fileContents = Storage::disk('ftp')->get($path);
                $pdfBase64 = base64_encode($fileContents);
                $this->pdf_vista = $pdfBase64;
            }
        } catch (\Throwable $th) {
            return null;
        }*/
    }
}
