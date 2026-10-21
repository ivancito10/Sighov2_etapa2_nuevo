<?php

namespace App\Livewire\SolicitudesPendientes;

use Livewire\Component;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\Solicitud;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use App\Services\ObtenerToken;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;

class SolicitudesPendientesMain extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante, $usuarioEntrega;
    public $cargoAprobado, $unidadAprobado, $cargoEntregado, $unidadEntregado, $cargoRechazante, $unidadRechazante;
    public $unidad_receptora, $motivo_anulacion, $estadoValidacion;
    public $medicamento, $count;
    public $fechaCreado;
    public $fechaCreacion, $userEdita, $fechaEdita,  $fueEntregado = false, $fueAprobado = false;

    public function render()
    {

        return view('livewire.solicitudes-pendientes.solicitudes-pendientes-main');
    }

    #[On('verSolicitud')]
    public function verSolicitud($data)
    {
        $this->resetearVariables();
        $this->dispatch('modalVerSolicitudesPendientes');
        $this->idSolicitud = $data;
        $this->mostrarSolicitud($this->idSolicitud);
    }


    public function obtenerDatosPersona($id_persona)
    {
        $datosPersonal = new ObtenerDatosPersonalSsu();
        return $datosPersonal->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerDatosUnidad($idUnidad)
    {
        $datosUnidad = new ObtenerDatosUnidadPorId();
        return $datosUnidad->obtenerDatosUnidadPorId($idUnidad);
    }

    public function mostrarSolicitud($id)
    {

        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud', 'usuarioEntregaSolicitud', 'usuarioEditor')
            ->find($id);

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
        $this->unidad_receptora = $this->obtenerDatosUnidad($solicitud->id_unidad_recepciona)['nombre'] ?? null;
        $this->estadoValidacion = $solicitud->estado_validacion;
        $this->motivo_anulacion = $solicitud->motivo_anulacion;
        $this->fecha_anulacion = Carbon::parse($solicitud->fecha_anulacion)->format('d/m/Y H:i:s');
        $this->usuarioSolicita = $solicitud->usuarioCreador->name;

        if ($solicitud->id_usuario_anula) {
            $this->usuarioAnula = $solicitud->usuarioAnulaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioAnulaSolicitud->id_persona);
            if ($datos_entregante) {
                $this->cargoRechazante = $datos_entregante['cargo_personal_ssu']['nombre'];
                $this->unidadRechazante = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
            }
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
            $this->cargoAprobado = $datos_aprobante['cargo_personal_ssu']['nombre'];
            $this->unidadAprobado = $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre'];

            // dd($datos_aprobante['cargo_personal_ssu']['nombre'], $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre']);
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
        $this->dispatch('anulaSolicitud');
    }
    #[On('anularSol')]
    public function anularSol($data)
    {
        try {
            $solicitud = Solicitud::find($this->idSolicitud);
            if ($solicitud) {
                $solicitud->estado_validacion = 'RECHAZADO';
                $solicitud->motivo_anulacion = $data;
                $solicitud->fecha_anulacion = Carbon::now();
                $solicitud->id_usuario_anula = auth()->user()->id;
                $solicitud->save();
            }
            // inicio de notificacion
            $serviceUnidad = new ObtenerDatosUnidadPorId();
            $unidadReceptora = $serviceUnidad->obtenerDatosUnidadPorId($solicitud->id_unidad_recepciona)['nombre'];
            if ($unidadReceptora === 'ALMACEN DE FARMACIA') {
                $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Auxiliar de Almacen de Farmacia'])->get();
                $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                foreach ($receptores as $receptor) {
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 3,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' =>  $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'ALMACEN DE MEDICAMENTOS - SOLICITUD RECHAZADA',
                        'mensaje' => 'La solicitud con codigo: ' . $solicitud->cod_solicitud . ' fue RECHAZADA. ' .
                            ' En fecha: ' . Carbon::now()->format('d/m/Y'),
                    ]);
                }
            }
            if ($unidadReceptora === 'FARMACIA') {
                $roles = Role::whereIn('name', ['Regente de Farmacia', 'Farmaceutica'])->get();
                $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                foreach ($receptores as $receptor) {
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 3,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' =>  $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'FARMACIA - SOLICITUD RECHAZADA',
                        'mensaje' => 'La solicitud con codigo: ' . $solicitud->cod_solicitud . ' fue RECHAZADA. ' .
                            ' En fecha: ' . Carbon::now()->format('d/m/Y'),
                    ]);
                }
            }
            // fin de notificacion
            $this->dispatch('pg:eventRefresh-solicitudesPendientesTable');
            $this->dispatch('solicitudRechazada');
        } catch (\Throwable $th) {
            $this->dispatch('errorFuncion');
        }
    }

    #[On('aprobarSolicitud')]
    public function aprobarSolicitud($data)
    {
        $this->idSolicitud = $data;
        $this->dispatch('aprobarSolicitudFuncion');
    }
    #[On('aprobandoSol')]
    public function aprobandoSol()
    {
        try {
            $solicitud = Solicitud::find($this->idSolicitud);
            if ($solicitud) {
                $solicitud->estado_validacion = 'APROBADO';
                $solicitud->fecha_aprobacion = Carbon::now();
                $solicitud->id_usuario_aprueba = auth()->user()->id;
                $solicitud->save();
            }
            // inicio de notificacion
            $serviceUnidad = new ObtenerDatosUnidadPorId();
            $unidadReceptora = $serviceUnidad->obtenerDatosUnidadPorId($solicitud->id_unidad_recepciona)['nombre'];
            if ($unidadReceptora === 'ALMACEN DE FARMACIA') {
                $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Auxiliar de Almacen de Farmacia'])->get();
                $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                foreach ($receptores as $receptor) {
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 3,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' =>  $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'ALMACEN DE FARMACIA - SOLICITUD APROBADA',
                        'mensaje' => 'La solicitud con codigo: ' . $solicitud->cod_solicitud . ' fue APROBADA y enviada a ALMACEN DE FARMACIA. ' .
                            ' En fecha: ' . Carbon::now()->format('d/m/Y'),
                    ]);
                }
            }
            if ($unidadReceptora === 'FARMACIA') {
                $roles = Role::whereIn('name', ['Regente de Farmacia', 'Farmaceutica'])->get();
                $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                foreach ($receptores as $receptor) {
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 3,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' =>  $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => 'FARMACIA - SOLICITUD APROBADA',
                        'mensaje' => 'La solicitud con codigo: ' . $solicitud->cod_solicitud . ' fue APROBADA y enviada a FARMACIA. ' .
                            ' En fecha: ' . Carbon::now()->format('d/m/Y'),
                    ]);
                }
            }
            // fin de notificacion
            $this->dispatch('pg:eventRefresh-solicitudesPendientesTable');
            $this->dispatch('solicitudAprobada');
        } catch (\Throwable $th) {
            $this->dispatch('errorFuncion');
        }
    }
}
