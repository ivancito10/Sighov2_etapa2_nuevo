<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\SolicitudesUnidades;

use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Farmacia\DetalleIngreso;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Models\Farmacia\Solicitud;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class SolicitudesUnidadesEntregar extends Component
{
    public $id_solicitud;

    public $cod_solicitud;

    public $cod_solicitud_numero;

    public $fecha_solicitud;

    public $unidad_solicitante;

    public $motivo;

    public $descripcion;

    public $estado_validacion;

    public $personal_solicitante;

    public $usuarioSolicita;

    public $fechaCreado;

    public $estadoValidacion;

    public $motivo_anulacion;

    public $fecha_anulacion;

    public $usuarioAnula;

    public $fecha_aprobacion;

    public $usuarioAprueba;

    public $fecha_entregado;

    public $usuarioEntrega;

    public $unidadSolicitante;

    public $cargoSolicitante;

    public $unidad_receptora;

    public $id_unidad_recepciona;

    public $count;

    public $cargoRechazante;

    public $unidadRechazante;

    public $cargoAprobado;

    public $unidadAprobado;

    public $cargoEntregado;

    public $unidadEntregado;

    public $medicamentosAgregados = [];

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.solicitudes-unidades.solicitudes-unidades-entregar');
    }

    public function mount()
    {
        $this->mostrarSolicitud($this->id_solicitud);
        $this->NotificacionesMedicamentosStockMenosCien();
        $this->NotificacionesMedicamentosPorVencer();
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();

        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerDatosUnidadReceptora($idUnidad)
    {
        $datosUnidad = new ObtenerDatosUnidadPorId();

        return $datosUnidad->obtenerDatosUnidadPorId($idUnidad);
    }

    #[On('guardarEntrega')]
    public function guardarEntrega()
    {
        // dd('asdasd');
        //$this->validate();
        $medicamentosSolicitados = MedicamentoSolicitud::where('id_solicitud', $this->id_solicitud)->get();
        DB::beginTransaction();
        try {
            $solicitudEntregada = Solicitud::where('id', $this->id_solicitud)->first();
            $solicitudEntregada->fecha_entregado = Carbon::now();
            $solicitudEntregada->id_usuario_entrega = auth()->user()->id;
            $solicitudEntregada->estado_validacion = 'ENTREGADO';
            $solicitudEntregada->save();

            // inicio de notificaciones
            $idUsuarioCreador = $solicitudEntregada->id_user_created;
            NotificacionUsuarios::create([
                'estado' => 'NO LEIDO',
                'prioridades' => 3,
                'id_usu_emisor' => auth()->user()->id,
                'id_usu_receptor' => $idUsuarioCreador,
                'fecha_hora_enviada' => Carbon::now(),
                'asunto' => 'ALMACEN DE MEDICAMENTOS - SOLICITUD ENTREGADA',
                'mensaje' => 'Su solicitud con codigo: ' . $solicitudEntregada->cod_solicitud . ' .Fue entregado exitosamente' .
                    '. En fecha: ' . Carbon::now()->format('d/m/Y'),
            ]);
            // fin de notificaciones

            $medicamentosSolicitados = MedicamentoSolicitud::where('id_solicitud', $this->id_solicitud)->get();
            foreach ($this->medicamentosAgregados as $medicamentoIngresado) {
                $medicamentoSolicitado = $medicamentosSolicitados->firstWhere('id_medicamento', $medicamentoIngresado['id_medicamento']);
                if ($medicamentoSolicitado) {
                    $medicamentoSolicitado->cantidad_entregada = $medicamentoIngresado['cantidad'];
                    $medicamentoSolicitado->save();
                }

                //LOGICA PARA EL STOCK
                $cantidad = $medicamentoIngresado['cantidad'];
                $this->procesarLotesEntrega($medicamentoIngresado['id_medicamento'], $cantidad);

                //FIN LOGICA PARA EL STOCK
            }
            DB::commit();
            // SE CREA EL PDF DE SOLICITUD DE UNIDAD A ALMACEN DE FARMACIA
            $url = route('farmacia.almacenfarmacia.solicitudes-unidades.pdf', ['modo' => 'create', 'rowId' => $solicitudEntregada->id]);
            $this->js("window.open('{$url}', '_blank');");

            $this->dispatch('pg:eventRefresh-AlmacenFarmaciaTable');
            $this->dispatch('entregadoExitosamente');

            return redirect()->route('almacen_farmacia.solicitudes.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('noEntregadoExitosamente');
        }
    }

    private function procesarLotesEntrega(int $idMedicamento, int $cantidad)
    {
        $lotes = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $this->id_unidad_recepciona)
            ->where('stock_actualizado', '>', 0)
            // ->where('fecha_vencimiento', '>', Carbon::now())
            ->orderBy('id', 'asc')
            ->get();

        foreach ($lotes as $lote) {
            if ($cantidad <= 0) {
                break;
            }

            $disponible = (int) $lote->stock_actualizado;
            if ($disponible <= 0) {
                continue;
            }

            $cantidadARestar = min($disponible, $cantidad);

            $datosParaMovimientos = [
                'id_corresponde' => $this->id_solicitud,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->id_unidad_recepciona,
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $this->unidadSolicitante,
                'nro_documento' => $this->cod_solicitud_numero,
                'dato_interesado' => $this->unidad_receptora,
                'nro_lote' => $lote->nro_lote,
                'precio_unitario_peps' => $lote->precio_unitario,
                'fecha_vencimiento' => $lote->fecha_vencimiento,
            ];

            $lote->stock_actualizado = $disponible - $cantidadARestar;
            $lote->save();

            $cantidad -= $cantidadARestar;

            $this->registrarMovimientoSolicitudUnidadAFarmacia($datosParaMovimientos);
        }
    }

    public function registrarMovimientoSolicitudUnidadAFarmacia($datosNecesarios)
    {
        //PARA LA TABLA DE MOVIMIENTOS 
        /*$movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->orderBy('fecha', 'desc')
            ->first();*/

        $saldoMedStock = MedicamentoStock::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->sum('stock_actualizado');

        $nuevoSaldo = $saldoMedStock;


        $nuevoMovimiento = Movimiento::create([
            'id_corresponde' => $datosNecesarios['id_corresponde'],
            'id_identificador_tabla' => 2,
            'dato_solicitante' => $datosNecesarios['dato_solicitante'],
            'nro_documento' => $datosNecesarios['nro_documento'],
            'fecha' => Carbon::now(),
            'dato_interesado' => $datosNecesarios['dato_interesado'],
            'cantidad_ingreso' => 0,
            'cantidad_salida' => $datosNecesarios['cantidad'],
            'saldo' => $nuevoSaldo,
            'id_estado_validacion' => 6,
            'id_medicamento' => $datosNecesarios['id_medicamento'],
            'id_unidad' => $datosNecesarios['id_unidad'],
            'nro_lote' => $datosNecesarios['nro_lote'],
            'id_user_created' => auth()->user()->id,
            'precio_unitario_peps' => $datosNecesarios['precio_unitario_peps'],
            'fecha_vencimiento' => $datosNecesarios['fecha_vencimiento'],
            'stock_real' => true,
        ]);

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }

    public function mostrarSolicitud($id)
    {
        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador.personaUser', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud', 'usuarioEntregaSolicitud')
            ->find($id);
        $this->cod_solicitud_numero = $solicitud->cod_solicitud;
        $this->cod_solicitud = $solicitud->cod_solicitud . ' / ' . Carbon::parse($solicitud->created_at)->year;
        $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
        $this->motivo = $solicitud->motivo;
        $this->fechaCreado = Carbon::parse($solicitud->created_at)->format('d/m/Y');
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
        $this->id_unidad_recepciona = $solicitud->id_unidad_recepciona;
        $this->unidad_receptora = $this->obtenerDatosUnidadReceptora($solicitud->id_unidad_recepciona)['nombre'];
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
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
            $datos_aprobante = $this->obtenerDatosPersona($solicitud->usuarioApruebaSolicitud->id_persona);
            $this->cargoAprobado = $datos_aprobante['cargo_personal_ssu']['nombre'];
            $this->unidadAprobado = $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioAprueba = null;
        }

        if ($solicitud->id_usuario_entrega) {
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioEntregaSolicitud->id_persona);
            $this->cargoEntregado = $datos_entregante['cargo_personal_ssu']['nombre'];
            $this->unidadEntregado = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioEntrega = null;
        }
        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'Sin dato';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'in dato';

        // MEDICAMENTOS INGRESADOS HASTA AUI LO DEJE, HAY QUE CAMBIAR LA ESTRUCUTURA DEL ARRAY, ATTE.LIZ
        $medicamentosSolicitados = MedicamentoSolicitud::where('id_solicitud', $id)
            ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
            ->get();

        foreach ($medicamentosSolicitados as $key => $medicamentoSol) {
            $stockMedicamento = MedicamentoStock::where('id_medicamento', $medicamentoSol->id_medicamento)
                ->where('id_unidad', $solicitud->id_unidad_recepciona)
                ->sum('stock_actualizado');

            $this->medicamentosAgregados[] = [
                'id_medicamento' => $medicamentoSol->id_medicamento,
                'codigo_medicamento' => $medicamentoSol->medicamentoMedicamentoSolicitud->codigo_medicamento,
                'nombre' => $medicamentoSol->medicamentoMedicamentoSolicitud->nombre,
                'forma_farmaceutica' => $medicamentoSol->medicamentoMedicamentoSolicitud->forma_farmaceutica,
                'concentracion' => $medicamentoSol->medicamentoMedicamentoSolicitud->concentracion,
                'cantidad' => $medicamentoSol->cantidad_solicitada,
                'stock_actual' => $stockMedicamento,
            ];
        }
    }

    // ========== REGLAS / VALIDACIÓN =============
    public function rules()
    {
        $rules = [];
        foreach ($this->medicamentosAgregados as $index => $med) {
            // Reglas sobre "medicamentosAgregados.$index.cantidad"
            $rules["medicamentosAgregados.$index.cantidad"] = [
                'required',
                'integer',
                'min:0',
                'max:' . ($med['stock_actual'] ?? 0),
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // Coincidir con la clave 'medicamentosAgregados.*.cantidad'
            'medicamentosAgregados.*.cantidad.max' => 'stock insuficiente',
        ];
    }

    // validación en tiempo real para "medicamentosAgregados"
    public function updatedMedicamentosAgregados($value, $key)
    {
        $this->validateOnly($key);

        [$index, $campo] = explode('.', $key);

        // Si el stock de este medicamento es 0, forzamos la cantidad a 0
        if (
            $campo === 'cantidad'
            && ($this->medicamentosAgregados[$index]['stock_actual'] ?? 0) == 0
        ) {
            $this->medicamentosAgregados[$index]['cantidad'] = 0;
        }
    }

    public function cancelarEntrega()
    {
        return redirect()->route('almacen_farmacia.solicitudes.index');
    }

    public function atras()
    {
        return redirect()->route('almacen_farmacia.solicitudes.index');
    }

    public function NotificacionesMedicamentosStockMenosCien()
    {
        $medicamentoStock = MedicamentoStock::get();
        $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Auxiliar de Almacen de Farmacia'])->get();
        $serviceUnidad = new ObtenerDatosUnidadPorId();
        $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
        $stockPorUnidadYMedicamento = $medicamentoStock->groupBy(['id_unidad', 'id_medicamento']);
        foreach ($stockPorUnidadYMedicamento as $idUnidad => $medicamentosPorUnidad) {
            foreach ($medicamentosPorUnidad as $idMedicamento => $medicamentos) {
                // $unidadSolicitante = $serviceUnidad->obtenerDatosUnidadPorId($idUnidad)['nombre'];
                $unidad = $serviceUnidad->obtenerDatosUnidadPorId($idUnidad);
                $unidadSolicitante = $unidad->nombre ?? 'Unidad desconocida';
                //dd($unidadSolicitante);
                $sumaStock = $medicamentos->sum('stock_actualizado');
                // dd($sumaStock);
                if ($sumaStock <= 100) {

                    $medicamento = Medicamento::find($idMedicamento);
                    //dd('entr', $medicamento);
                    $nombreMedicamento = $medicamento->nombre;
                    //dd($receptores);
                    foreach ($receptores as $receptor) {

                        NotificacionUsuarios::create([
                            'estado' => 'NO LEIDO',
                            'prioridades' => 3,
                            'id_usu_emisor' => auth()->user()->id,
                            'id_usu_receptor' => $receptor->model_id,
                            'fecha_hora_enviada' => Carbon::now(),
                            'asunto' => 'MEDICAMENTOS - CANTIDAD',
                            'mensaje' => 'El medicamento: ' . $nombreMedicamento . ' en la unidad ' . $unidadSolicitante . ' tiene un stock total de menos de 100 unidades.',
                        ]);
                    }
                }
            }
        }
    }

    public function NotificacionesMedicamentosPorVencer()
    {
        $today = Carbon::now();
        $medicamentoStock = MedicamentoStock::get();
        foreach ($medicamentoStock as $key => $medStock) {
            if ($medStock->stock_actualizado > 0) {
                $detalleIngreso = DetalleIngreso::where('id_medicamento', $medStock->id_medicamento)
                    ->where('nro_lote', $medStock->nro_lote)->first();
                if ($detalleIngreso) {
                    $fechaVencimiento = Carbon::parse($detalleIngreso->fecha_vencimiento);
                    if ($fechaVencimiento->isPast()) {
                        $this->EnviarNotificacion($medStock, 'VENCIDO', 'El medicamento está vencido.');
                    } elseif ($fechaVencimiento->diffInDays($today) <= 5) {
                        $this->EnviarNotificacion($medStock, 'POR VENCER', 'El medicamento está próximo a vencer en 5 días.');
                    }
                }
            }
        }
    }

    public function EnviarNotificacion($medStock, $estado, $mensaje)
    {
        $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Auxiliar de Almacen de Farmacia'])->get();
        $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
        $medicamento = Medicamento::find($medStock->id_medicamento);
        $nombreMedicamento = $medicamento->nombre;
        foreach ($receptores as $receptor) {
            NotificacionUsuarios::create([
                'estado' => 'NO LEIDO',
                'prioridades' => 3,
                'id_usu_emisor' => auth()->user()->id,
                'id_usu_receptor' => $receptor->model_id,
                'fecha_hora_enviada' => Carbon::now(),
                'asunto' => 'MEDICAMENTOS - ' . $estado,
                'mensaje' => 'El medicamento: ' . $nombreMedicamento . $mensaje,
            ]);
        }
    }
}
