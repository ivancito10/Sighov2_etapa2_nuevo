<?php

namespace App\Livewire\AlmacenGeneral\SolicitudesUnidades;

//use App\Http\Controllers\Farmacia\PdfSolicitudUnidadesController;

use App\Models\AlmacenGeneral\MaterialSolicitud;
use App\Models\AlmacenGeneral\MaterialStock;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\AlmacenGeneral\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\ObtenerDatosPersonalSsu;

class SolicitudesUnidadesMain extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante;
    public $cargoAprobado, $unidadAprobado, $cargoEntregado, $unidadEntregado, $cargoRechazante, $unidadRechazante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion, $usuarioEntrega;
    public $material, $count;
    public $fechaCreado;
    public $fueAprobado = false, $fueEntregado = false, $fechaCreacion, $fechaEdita, $userEdita;

    public function render()
    {
        return view('livewire.almacen-general.solicitudes-unidades.solicitudes-unidades-main');
    }

    #[On('ver_solicitud')]
    public function ver_solicitud($data)
    {
        $this->resetearVariables();
        $this->idSolicitud = $data;
        $this->mostrarSolicitud($data);
        $this->dispatch('verSolicitudAlmacenGeneral');
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

        // MEDICAMENTOS INGRESADOS con STOCK
        $subconsultaStock = MaterialStock::selectRaw('SUM(stock_actualizado)')
            ->whereColumn('material_stock.id_material', 'material_solicitud.id_material')
            ->where('id_unidad', $solicitud->id_unidad_recepciona);

        $this->material = MaterialSolicitud::where('id_solicitud', $id)
            ->select('*')
            ->selectSub($subconsultaStock, 'stock_total')
            ->with('materialMaterialSolicitud', 'solicitudMaterialSolicitud')
            ->get();

        //dd($this->material);
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
        $url = route('almacen_general.solicitud_unidades.pdf', ['modo' => 'ver', 'rowId' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");
    }


    #[On('enviarAprobacionSolicitud')]
    public function enviarAprobacionSolicitud($data)
    {
        $this->idSolicitud = $data;
        $this->dispatch('preguntarEnviarAprobacion');
    }

    public function verificacionMaterialesAgregados(Solicitud $solicitud)
    {
        $materialesAgregados = [];

        $materialesSolicitados = MaterialSolicitud::where('id_solicitud', $solicitud->id)
            ->with('materialMaterialSolicitud', 'solicitudMaterialSolicitud')
            ->get();

        foreach ($materialesSolicitados as $key => $materialSol) {
            $stockMaterial = MaterialStock::where('id_material', $materialSol->id_material)
                ->where('id_unidad', $solicitud->id_unidad_recepciona)
                ->sum('stock_actualizado');

            $materialesAgregados[] = [
                'id_material' => $materialSol->id_material,
                'codigo' => $materialSol->materialMaterialSolicitud->codigo,
                'nombre_material' => $materialSol->materialMaterialSolicitud->nombre_material,
                'unidad' => $materialSol->materialMaterialSolicitud->unidad,
                'descripcion' => $materialSol->materialMaterialSolicitud->descripcion,
                'cantidad' => $materialSol->cantidad_solicitada,
                'cantidad_original' => $materialSol->cantidad_solicitada_original,
                'stock_actual' => $stockMaterial,
            ];
        }

        return $materialesAgregados;
    }

    #[On('guardarEnviarAprobacionSolicitud')]
    public function guardarEnviarAprobacionSolicitud()
    {
        DB::beginTransaction();
        try {
            $solicitudRevisada = Solicitud::find($this->idSolicitud);
            $materialesAColeccion = collect($this->verificacionMaterialesAgregados($solicitudRevisada));

            $hayErroresDeStock = $materialesAColeccion->contains(function ($materialcito) {
                $cantidad = floatval($materialcito['cantidad'] ?? 0);
                $stock = floatval($materialcito['stock_actual'] ?? 0);
                return $stock > 0 && $cantidad > $stock;
            });

            $hayErroresDeSolicitud = $materialesAColeccion->contains(function ($materialcito) {
                $cantidad = floatval($materialcito['cantidad'] ?? 0);
                $solicitado = floatval($materialcito['cantidad_original'] ?? 0);

                return $cantidad > $solicitado;
            });

            $hayVacios = $materialesAColeccion->contains(function ($materialcito) {
                return empty($materialcito['cantidad']) && $materialcito['cantidad'] < 0;
            });

            if ($hayErroresDeStock) {
                $this->dispatch('cantidadSuperaStock');
                return;
            }

            if ($hayErroresDeSolicitud) {
                $this->dispatch('cantidadSuperaSolicitado');
                return;
            }

            if ($hayVacios) {
                $this->dispatch('cantidadVacia');
                return;
            }

            $solicitudRevisada->estado_validacion = 'REVISADO';
            $solicitudRevisada->fecha_revisado = Carbon::now();
            $solicitudRevisada->id_user_revisado = auth()->user()->id;
            $solicitudRevisada->save();
            DB::commit();
            $this->dispatch('pg:eventRefresh-AlmacenGeneralSolicitudesTable');
            $this->dispatch('solicitudRevisada');
        } catch (\Throwable $th) {
            //dd($th);
            DB::rollBack();
            $this->dispatch('errorRevision');
        }
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
            $this->dispatch('pg:eventRefresh-AlmacenGeneralSolicitudesTable');
            $this->dispatch('solicitudAnulada');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('errorAnulacion');
        }
    }
}
