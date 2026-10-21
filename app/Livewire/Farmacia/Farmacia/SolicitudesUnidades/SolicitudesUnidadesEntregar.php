<?php

namespace App\Livewire\Farmacia\Farmacia\SolicitudesUnidades;

use Livewire\Component;
use App\Models\Farmacia\Solicitud;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use Livewire\Attributes\On;
use App\Models\User;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\DetalleIngreso;

class SolicitudesUnidadesEntregar extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante, $usuarioEntrega;
    public $unidad_receptora, $motivo_anulacion, $estadoValidacion, $id_unidad_recepciona, $cargoRechazante, $unidadRechazante, $cargoAprobado, $unidadAprobado,
        $cargoEntregado, $unidadEntregado, $cod_solicitudNumero;
    public $medicamento, $count;
    public $fechaCreado;
    public $medicamentosAgregados = [];


    public function render()
    {
        return view('livewire.farmacia.farmacia.solicitudes-unidades.solicitudes-unidades-entregar');
    }
    public function mount()
    {
        $this->mostrarSolicitud($this->idSolicitud);
        // $this->NotificacionesMedicamentosStockMenosCien();
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
        //dd('entra');
        //dd($this->id_unidad_recepciona);
        //dd($this->medicamentosAgregados);
        $this->validate();
        $medicamentosSolicitados = MedicamentoSolicitud::where('id_solicitud', $this->idSolicitud)->get();
        DB::beginTransaction();
        try {
            $solicitudEntregada = Solicitud::where('id', $this->idSolicitud)->first();
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
                'asunto' => 'FARMACIA - SOLICITUD ENTREGADA',
                'mensaje' => 'Su solicitud con codigo: ' . $solicitudEntregada->cod_solicitud . ' .Fue entregado exitosamente' .
                    '. En fecha: ' . Carbon::now()->format('d/m/Y'),
            ]);
            // fin de notificaciones
            $loteMedicamento = 0;
            $medicamentosSolicitados = MedicamentoSolicitud::where('id_solicitud', $this->idSolicitud)->get();
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
            // SE CREA EL PDF DE SOLICUTD DE UNIDADES A FARMACIA EN FTP
            $url = route('farmacia.farmacia.solicitud-unidad.pdf', ['modo' => 'create', 'id' => $solicitudEntregada->id]);
            $this->js("window.open('{$url}', '_blank');");

            $this->dispatch('entregadoExitosamente');
            return redirect()->route('farmacia.solicitudes.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('noEntregadoExitosamente');
        }

        //dd($medicamentoStock);
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
                'id_corresponde' => $this->idSolicitud,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->id_unidad_recepciona,
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $this->unidadSolicitante,
                'nro_documento' => $this->cod_solicitudNumero,
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
            ->orderBy('id', 'desc')
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

        $this->cod_solicitud = $solicitud->cod_solicitud . ' / ' .  Carbon::parse($solicitud->created_at)->year;
        $this->cod_solicitudNumero = $solicitud->cod_solicitud;
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

        $this->unidad_receptora = $this->obtenerDatosUnidadReceptora($solicitud->id_unidad_recepciona)['nombre'] ?? 'SIN DATO';

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
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
            $datos_aprobante = $this->obtenerDatosPersona($solicitud->usuarioApruebaSolicitud->id_persona);
            $this->cargoAprobado = $datos_aprobante['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadAprobado = $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        } else {
            $this->usuarioAprueba = null;
        }

        if ($solicitud->id_usuario_entrega) {
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioEntregaSolicitud->id_persona);
            $this->cargoEntregado = $datos_entregante['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            $this->unidadEntregado = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        } else {
            $this->usuarioEntrega = null;
        }
        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

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
                'cantidad' => $stockMedicamento > 0 ? $medicamentoSol->cantidad_solicitada : 0,
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
        return redirect()->route('farmacia.solicitudes.index');
    }

    public function atras()
    {
        return redirect()->route('farmacia.solicitudes.index');
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
                'mensaje' => 'El medicamento: ' . $nombreMedicamento .  $mensaje,
            ]);
        }
    }
}
