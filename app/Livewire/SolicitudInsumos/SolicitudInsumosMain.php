<?php

namespace App\Livewire\SolicitudInsumos;

use App\Models\Farmacia\MedicamentoSolicitud;
use Livewire\Attributes\On;
use App\Models\Farmacia\Solicitud;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class SolicitudInsumosMain extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion, $unidad_receptora;
    public $medicamento, $count;
    public $fechaCreado;

    public $unidadAprueba, $cargoAprueba;
    public $unidadAnula, $cargoAnula;
    public $usuarioEntrega, $unidadEntrega, $cargoEntrega, $esEncargado = false, $esPersonal = false;
    public $fechaCreacion, $userEdita, $fechaEdita,  $fueEntregado = false, $fueAprobado = false;
    public $id_unidad_servicio;
    public $nombreUnidadServicio;
    public $piso;
    public $pisoSwitch;

    public function render()
    {
        return view('livewire.solicitud-insumos.solicitud-insumos-main');
    }
    public function mount()
    {
        $this->verificarCargo();
    }
    public function verificarCargo()
    {
        $id_persona = auth()->user()->id_persona;
        // dd($id_persona);
        $datosPersonal =  new ObtenerDatosPersonalSsu();
        $personalSsu = $datosPersonal->obtenerDatosPersonalSsu($id_persona);
        //dd($personalSsu);
        if ($personalSsu) {
            //  $encargado = $personalSsu['cargo_personal_ssu']['encargado_unidad'];
            // dd($encargado);
            //  if ($encargado == true) {
            //$this->esEncargado = true;
            $this->esPersonal = true;
            // $this->dispatch('errorAnulacion');
            // }
        }
    }

    public function redirigirRegistrarSolicitud()
    {
        return redirect()->route('solicitud_insumos.create');
    }

    public static function obtenerNombreUnidadServicioSolicitante($idUnidadServicio)
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidadServicio)['nombre'];
    }

    #[On('verSolicitudInsumos')]
    public function verSolicitudInsumos($data)
    {
        $this->resetearVariables();
        $this->idSolicitud = $data;
        $this->mostrarSolicitud($this->idSolicitud);
        $this->dispatch('modalVerSolicitudInsumos');
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    public function mostrarSolicitud($id)
    {
        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador', 'usuarioCreador.personaUser', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud', 'usuarioEntregaSolicitud', 'usuarioEditor')
            ->find($id);
        $this->cod_solicitud = $solicitud->cod_solicitud . ' / ' .  Carbon::parse($solicitud->created_at)->year;
        $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
        $this->motivo = $solicitud->motivo;
        $this->fechaCreado = Carbon::parse($solicitud->created_at)->format('d/m/Y H:i:s');
        $this->nombreUnidadServicio = $this->obtenerNombreUnidadServicioSolicitante($solicitud->id_unidad_servicio);
       // dd($this->nombreUnidadServicio);
        $this->piso = null;
        $this->pisoSwitch = false;
        if ($solicitud->piso !== null) {
            $this->piso = $solicitud->piso;
            $this->pisoSwitch = true;
        }
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
            $datosAnula = $this->obtenerDatosPersona($solicitud->usuarioAnulaSolicitud->id_persona);
            $this->usuarioAnula = $solicitud->usuarioAnulaSolicitud->name;
            $this->unidadAnula = $datosAnula['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
            $this->cargoAnula = $datosAnula['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        }
        $this->fecha_aprobacion = Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i:s');
        $this->fecha_entregado = Carbon::parse($solicitud->fecha_entregado)->format('d/m/Y H:i:s');
        if ($solicitud->id_usuario_aprueba) {
            $this->fueAprobado = true;
            $datosAprueba = $this->obtenerDatosPersona($solicitud->usuarioApruebaSolicitud->id_persona);
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
            $this->unidadAprueba = $datosAprueba['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
            $this->cargoAprueba = $datosAprueba['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        }
        // dd($solicitud->id_usuario_entrega);
        if ($solicitud->id_usuario_entrega) {
            $this->fueEntregado = true;
            $datosEntrega = $this->obtenerDatosPersona($solicitud->usuarioEntregaSolicitud->id_persona);
            // dd($datosEntrega);
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;
            $this->unidadEntrega = $datosEntrega['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
            $this->cargoEntrega = $datosEntrega['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        }

        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

        $unRecep = new ObtenerDatosUnidadPorId();
        $this->unidad_receptora = $unRecep->obtenerDatosUnidadPorId($solicitud->id_unidad_recepciona)['nombre'] ?? 'SIN DATO';


        // MEDICAMENTOS INGRESADOS
        $this->medicamento = MedicamentoSolicitud::where('id_solicitud', $id)
            ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
            ->get();
        // datos para seguimiento
        $this->fechaCreacion = $solicitud->created_at;
        $this->fechaEdita = $solicitud->updated_at;
        $this->userEdita = $solicitud->usuarioEditor?->name;
    }


    #[On('verPDF')]
    public function verPDF($rowId)
    {
        //dd("Aqui es el PDF".$rowId);
        $url = route('farmacia.solicitudinsumos.pdf', ['modo' => 'ver', 'id' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");
    }

    #[On('anularSolicitud')]
    public function anularSolicitud($data)
    {
        $this->idSolicitud = $data;
        $this->dispatch('anular_solicitud');
    }

    #[On('guardar_anular_solicitud')]
    public function guardar_anular_solicitud($data)
    {
        DB::beginTransaction();
        try {
            $solicitudAnulada = Solicitud::find($this->idSolicitud);
            $solicitudAnulada->estado_validacion = 'ANULADO';
            $solicitudAnulada->fecha_anulacion = Carbon::now();
            $solicitudAnulada->id_usuario_anula = auth()->user()->id;
            $solicitudAnulada->motivo_anulacion = $data;
            $solicitudAnulada->save();
            DB::commit();
            $this->dispatch('pg:eventRefresh-solitudInsumosTable');
            $this->dispatch('solicitudAnulada');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('errorAnulacion');
        }
    }
    public function resetearVariables()
    {
        $this->reset('fueAprobado', 'fueEntregado');
    }
}
