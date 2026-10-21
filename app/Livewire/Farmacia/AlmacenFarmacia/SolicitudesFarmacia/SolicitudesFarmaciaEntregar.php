<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\SolicitudesFarmacia;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Farmacia\Solicitud;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use Livewire\Attributes\On;
use App\Services\ListaUnidades;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;

class SolicitudesFarmaciaEntregar extends Component
{
    public $id_solicitud;
    public $cod_solicitud, $fecha_solicitud,
        $unidad_solicitante, $motivo, $descripcion,
        $estado_validacion, $personal_solicitante, $usuarioSolicita,
        $unidad, $cargo, $cod_solicitud_mostrar;
    public $cantidad, $medicamentosStock;
    public $nuevoMedStockFarmacia;

    public $medicamento = [];
    public $usuarioAprueba, $fecha_aprobacion, $unidadAprobado, $cargoAprobado;

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.solicitudes-farmacia.solicitudes-farmacia-entregar');
    }

    public function mount()
    {
        $this->mostrarSolicitud($this->id_solicitud);
    }

    public function obtenerIdAlmacen()
    {
        //$datosUnidades = new ListaUnidades();
        //return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
        return 89;
    }

    public function obtenerIdFarmacia()
    {
        //$datosUnidades = new ListaUnidades();
        //return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
        return 76;
    }
    public function mostrarSolicitud($id)
    {
        if ($id) {
            $solicitud = Solicitud::with('usuarioCreador', 'usuarioApruebaSolicitud', 'usuarioEntregaSolicitud')->find($id);
            $this->cod_solicitud = $solicitud->cod_solicitud;
            $this->cod_solicitud_mostrar = $solicitud->cod_solicitud . ' / ' .  Carbon::parse($solicitud->created_at)->year;
            $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
            $id_unidad_solicitante = $solicitud->id_unidad_solicita;
            $this->motivo = $solicitud->motivo;
            $this->descripcion = $solicitud->descripcion;
            $this->estado_validacion = $solicitud->estado_validacion;

            $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
            $this->unidad = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
            $this->cargo = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

            $this->usuarioSolicita = $solicitud->usuarioCreador->name;
            // dd($solicitud);
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name ?? 'SIN DATO';
            $this->fecha_aprobacion = Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y');
            $persona_aprueba =  $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
            $this->unidadAprobado = $persona_aprueba['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
            $this->cargoAprobado = $persona_aprueba['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';


            // MEDICAMENTOS INGRESADOS
            $this->medicamento = MedicamentoSolicitud::where('id_solicitud', $id)
                ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
                ->get()

                ->map(function ($med) {
                    $idMedicamento = $med->medicamentoMedicamentoSolicitud->id ?? null;
                    $stockActual = 0;
                    if ($idMedicamento) {
                        $stockActual = MedicamentoStock::where('id_medicamento', $idMedicamento)
                            ->where('id_unidad', $this->obtenerIdAlmacen())
                            ->sum('stock_actualizado');
                    }
                    return [
                        'id'                  => $med->id,
                        'cantidad_solicitada' => $stockActual == 0 ? 0 : null,
                        'codigo_medicamento'  => $med->medicamentoMedicamentoSolicitud->codigo_medicamento ?? null,
                        'nombre'              => $med->medicamentoMedicamentoSolicitud->nombre ?? null,
                        'forma_farmaceutica'  => $med->medicamentoMedicamentoSolicitud->forma_farmaceutica ?? null,
                        'concentracion'  => $med->medicamentoMedicamentoSolicitud->concentracion ?? null,
                        'stock'               => $stockActual,
                        'id_solicitud'        => $med->id_solicitud,
                        'id_medicamento'      => $med->id_medicamento,

                    ];
                })

                ->toArray();
        }
    }

    public function rules()
    {
        $rules = [];
        // Recorremos cada fila del array $this->medicamento
        foreach ($this->medicamento as $index => $med) {
            // 'max:' se define dinámicamente con el stock de ese medicamento
            $rules["medicamento.$index.cantidad_solicitada"] = [
                'required',
                'integer',
                'min:0',
                'max:' . ($med['stock'] ?? 0),
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'medicamento.*.cantidad_solicitada.max' => 'stock insuficiente',
        ];
    }

    public function updatedMedicamento($value, $key)
    {
        //dd($value);   
        // Validación normal (ej. validateOnly($key))
        $this->validateOnly($key);
        [$index, $campo] = explode('.', $key);
        // Si el stock de este medicamento es 0, forzamos la cantidad a 0
        if (
            $campo === 'cantidad_solicitada'
            && ($this->medicamento[$index]['stock'] ?? 0) == 0
        ) {
            $this->medicamento[$index]['cantidad_solicitada'] = 0;
        }
    }

    public function obtenerDatosPersona($id_persona)
    {
        $obtenerDatosPers = new ObtenerDatosPersonalSsu();
        return $obtenerDatosPers->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerDatosUnidad($id_unidad)
    {
        $obtenerDatosUnidad = new ObtenerDatosUnidadPorId();
        return $obtenerDatosUnidad->obtenerDatosUnidadPorId($id_unidad);
    }

    #[On('registrarSolicitud')]
    public function registrarSolicitud()
    {
        //dd($this->medicamento);

        $this->validate();

        DB::beginTransaction();
        try {
            foreach ($this->medicamento as $med) {
                $solitudMedicamentoActualizado = MedicamentoSolicitud::find($med['id']);
                if ($solitudMedicamentoActualizado) {
                    $solitudMedicamentoActualizado->cantidad_solicitada = null;
                    $solitudMedicamentoActualizado->cantidad_entregada = $med['cantidad_solicitada'];
                }
                $solitudMedicamentoActualizado->save();

                // ---------- INICIO DE MEDICAMENTO STOCK -----------

                $arrayDatosNecesariosParaMedicamentoStock = [
                    'id_almacen' => $this->obtenerIdAlmacen(),
                    'id_medicamento' => $med['id_medicamento'],
                    'cantidad_entregada' => $med['cantidad_solicitada'],
                    'id_farmacia' => $this->obtenerIdFarmacia()
                ];

                $arrayDatosNecesariosParaMovimiento = [
                    'id_corresponde' => $this->id_solicitud,
                    'dato_solicitante' => 'FARMACIA',
                    'nro_documento' => $this->cod_solicitud,
                    'datos_interesado' => 'ALMACEN DE FARMACIA',
                    'id_medicamento' => $med['id_medicamento']
                ];


                $this->registrarMedicamentoStockSolicitudDeFarmaciaAAlmacen($arrayDatosNecesariosParaMedicamentoStock, $arrayDatosNecesariosParaMovimiento);
            }

            $solicitudEntregada = Solicitud::find($this->id_solicitud);

            if ($solicitudEntregada) {
                $solicitudEntregada->estado_validacion = 'ENTREGADO';
                $solicitudEntregada->fecha_entregado = Carbon::now()->format('Y-m-d');
                $solicitudEntregada->id_usuario_entrega = auth()->user()->id;
            }

            $solicitudEntregada->save();
            // inicio de notificacion 
            // PARA LOS REGENTES DE FARMACIA
            $roles = Role::whereIn('name', ['Regente de Farmacia'])->get();
            $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
            $nombre_usuario = $solicitudEntregada->usuarioCreador->name;
            foreach ($receptores as $receptor) {
                NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 3,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' =>  $receptor->model_id,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'ALMACEN DE MEDICAMENTOS - SOLICITUD ENTREGADA',
                    'mensaje' => 'Se ha realizado la entrega de la solicitud con codigo: ' . $solicitudEntregada->cod_solicitud . ' de la unidad: FARMACIA' .
                        '. Realizado por el usuario: ' . $nombre_usuario .
                        '. En fecha: ' . Carbon::now()->format('d/m/Y'),
                ]);
            }
            // NOTIFICACION AL USUARIO CREADOR:
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
            // fin de notificacion

            DB::commit();

            //SE CREA EL PDF DE SOLICITUD DE FARMACIA A ALMACEN DE FARMACIA
            $url = route('farmacia.almacenfarmacia.solicitud-farmacia.pdf', ['modo' => 'create', 'rowId' => $solicitudEntregada->id]);
            $this->js("window.open('{$url}', '_blank');");

            $this->dispatch('entregaExitosa');
            return redirect()->route('farmacia.almacen-farmacia.solicitudes-farmacia.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('errorCatch');
        }
    }

    public function registrarMedicamentoStockSolicitudDeFarmaciaAAlmacen($arrayDatos, $arrayDatosMovimiento)
    {
        //dd($arrayDatos, $arrayDatosMovimiento);
        $medStockAlmacenPorLotes = MedicamentoStock::where('id_unidad', $arrayDatos['id_almacen'])
            ->where('id_medicamento', $arrayDatos['id_medicamento'])
            ->where('stock_actualizado', '>', 0)
            //->where('fecha_vencimiento', '>', Carbon::now())
            ->orderBy('nro_lote', 'asc')
            ->get();

        if (count($medStockAlmacenPorLotes) > 0) {
            //PARA REGISTRAR EL STOCK EN MEDICAMENTO STOCK PERTENECIENTE A ALMACEN
            $cantidadARestar = $arrayDatos['cantidad_entregada'];
            foreach ($medStockAlmacenPorLotes as $loteAlmacen) {
                if ($cantidadARestar > 0) {
                    if ($loteAlmacen->stock_actualizado > 0) {
                        if ($cantidadARestar >= $loteAlmacen->stock_actualizado) {
                            $this->registrarMovimientoSolicitudDeFarmaciaAAlmacen(
                                $arrayDatosMovimiento,
                                false,
                                $loteAlmacen->stock_actualizado,
                                $arrayDatos['cantidad_entregada'],
                                $arrayDatos['id_almacen'],
                                $loteAlmacen->nro_lote,
                                $loteAlmacen->precio_unitario,
                                $loteAlmacen->fecha_vencimiento
                            );
                            $cantidadARestar = $cantidadARestar - $loteAlmacen->stock_actualizado;
                            $loteAlmacen->stock_actualizado = 0;
                            $loteAlmacen->id_user_updated = auth()->user()->id;
                            $loteAlmacen->save();
                        } else {
                            $this->registrarMovimientoSolicitudDeFarmaciaAAlmacen(
                                $arrayDatosMovimiento,
                                false,
                                $cantidadARestar,
                                $arrayDatos['cantidad_entregada'],
                                $arrayDatos['id_almacen'],
                                $loteAlmacen->nro_lote,
                                $loteAlmacen->precio_unitario,
                                $loteAlmacen->fecha_vencimiento
                            );
                            $loteAlmacen->stock_actualizado = $loteAlmacen->stock_actualizado - $cantidadARestar;
                            $loteAlmacen->id_user_updated = auth()->user()->id;
                            $loteAlmacen->save();
                            $cantidadARestar = 0;
                        }
                    }
                }
            }

            //PARA REGISTRAR EL NUEVO INGRESO EN EL "ALMACEN IMAGINARIO DE FARMACIA" POR ENDE 
            //TIENE QUE TENER NUEVO NUMERO DE LOTE POR MEDICAMENTO TAMBIEN, LO MISMO SE REPLICARÁ
            //EM ALMACEN DE FARMACIA
            $ultimaFechaVencimiento = $medStockAlmacenPorLotes->last()->fecha_vencimiento;
            $ultimoPrecioUnitario = $medStockAlmacenPorLotes->last()->precio_unitario;
            $nroLoteFarmaciaUltimo = MedicamentoStock::where('id_medicamento', $arrayDatos['id_medicamento'])
                ->where('id_unidad', $arrayDatos['id_farmacia'])
                ->orderBy('nro_lote', 'desc')
                ->first();

            //dd($arrayDatos);

            $nroLoteSolicitudFarmaciaPorMedicamento = $nroLoteFarmaciaUltimo ? $nroLoteFarmaciaUltimo->nro_lote + 1 : 1;

            $nuevoStockFarmacia = MedicamentoStock::create([
                'cantidad_inicial' => $arrayDatos['cantidad_entregada'],
                'precio_unitario' => $ultimoPrecioUnitario,
                'stock_actualizado' => $arrayDatos['cantidad_entregada'],
                'nro_lote' => $nroLoteSolicitudFarmaciaPorMedicamento,
                'id_medicamento' => $arrayDatos['id_medicamento'],
                'id_user_created' => auth()->user()->id,
                'id_unidad' => $arrayDatos['id_farmacia'],
                'ajuste_contable' => false,
                'fecha_vencimiento' => $ultimaFechaVencimiento,
            ]);

            $this->registrarMovimientoSolicitudDeFarmaciaAAlmacen(
                $arrayDatosMovimiento,
                true,
                $cantidadARestar,
                $arrayDatos['cantidad_entregada'],
                $arrayDatos['id_farmacia'],
                $nroLoteSolicitudFarmaciaPorMedicamento,
                $ultimoPrecioUnitario,
                $ultimaFechaVencimiento,
            );
            //PARA REGISTRAR UN NUEVO "INGRESO" EN EL "ALMACEN" DE FARMACIA (CONTAMOS CON QUE 
            // FARMACIA TIENE UNA ESPECIE DE ALMACEN IMAGINARIO. POR ENDE, LA CANTIDAD )
        }
    }

    public function registrarMovimientoSolicitudDeFarmaciaAAlmacen(
        $arrayDatosMovimiento,
        $ingresoSwitch,
        $cantidadARestar,
        $cantidadEntregada,
        $idUnidad,
        $nroLote,
        $peps,
        $fechaVencimiento,
    ) //ingreso = true, salida = false
    {
        $movimientoPrevioPorUnidadYMedicamento = Movimiento::where('id_unidad', $idUnidad)
            ->where('id_medicamento', $arrayDatosMovimiento['id_medicamento'])
            ->orderBy('fecha', 'desc')
            ->first();

        $saldoMedStock = MedicamentoStock::where('id_medicamento', $arrayDatosMovimiento['id_medicamento'])
            ->where('id_unidad', $idUnidad)
            ->sum('stock_actualizado');

        $cantidadIngreso = 0;
        $cantidadSalida = 0;
        $saldo = 0;
        $idEstadoValidacion = 5;

        if ($ingresoSwitch) {
            if ($movimientoPrevioPorUnidadYMedicamento) {
                $nroLote = $movimientoPrevioPorUnidadYMedicamento->nro_lote + 1;
                $cantidadIngreso = $cantidadEntregada;
                $saldo = $saldoMedStock;
                $idEstadoValidacion = 6;
            } else {
                $nroLote = 1;
                $cantidadIngreso = $cantidadEntregada;
                $saldo = $cantidadEntregada;
                $idEstadoValidacion = 6;
            }
        } else {
            if ($movimientoPrevioPorUnidadYMedicamento) {
                $cantidadSalida = $cantidadARestar;
                $saldo = $saldoMedStock - $cantidadARestar;
            }
        }

        $nuevoMovimientoPorLote = [
            'id_corresponde' => $arrayDatosMovimiento['id_corresponde'],
            'id_identificador_tabla' => 2,
            'dato_solicitante' => $arrayDatosMovimiento['dato_solicitante'],
            'nro_documento' => $arrayDatosMovimiento['nro_documento'],
            'fecha' => Carbon::now(),
            'dato_interesado' => $arrayDatosMovimiento['datos_interesado'],
            'cantidad_ingreso' => $cantidadIngreso,
            'cantidad_salida' => $cantidadSalida,
            'saldo' => $saldo,
            'id_estado_validacion' => $idEstadoValidacion,
            'id_medicamento' => $arrayDatosMovimiento['id_medicamento'],
            'id_user_created' => auth()->user()->id,
            'id_unidad' => $idUnidad,
            'cierre_gestion' => false,
            'nro_lote' => $nroLote,
            'precio_unitario_peps' => $peps,
            'fecha_vencimiento' => $fechaVencimiento,
            'stock_real' => true,
        ];

        $nuevoMovimiento = Movimiento::create($nuevoMovimientoPorLote);
    }

    public function atras()
    {
        return redirect()->route('farmacia.almacen-farmacia.solicitudes-farmacia.index');
    }
}
