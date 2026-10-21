<?php

namespace App\Livewire\AlmacenGeneral\Ingresos;

use App\Models\AlmacenGeneral\Factura;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\IngresoMaterial;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\ProveedorCumple;
use App\Models\Farmacia\InstitucionExterna;
use App\Models\Farmacia\Proceso;
use App\Models\Farmacia\TipoProceso;
use App\Services\ListaUnidades;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class IngresosRegister extends Component
{
    public $institucionesExternas;
    public $procesos;
    public $nombreTipoProceso;
    public $monto_total_factura;
    public $materiales;
    public $id_material;
    public $materialesAgregados = [];
    public $monto_total;
    #[Validate]
    public $nroLote;
    public $descripcionMaterial;
    public $unidadMostrar;
    public $descripcionMostrar;
    public $precioUnitario;
    public $cantidad;
    public $fecha_vencimiento;
    public $nombreMaterial;
    #[Validate]
    public $id_institucion_externa;
    #[Validate]
    public $fecha_ingreso;
    #[Validate]
    public $cod_ingreso;
    #[Validate]
    public $fecha_contrato;
    #[Validate]
    public $fecha_recepcion;
    #[Validate]
    public $hora_recepcion;
    #[Validate]
    public $cod_cuce;
    #[Validate]
    public $fecha_factura;

    //AGREGADOS POSTERIORMENTE

    public $facturaAgregadas = [];

    public $monto_total_facturas = 0.00;

    public $id_tipo_proceso;

    public $facturas = [];

    public $nro_factura;

    public $nota_remision;

    public $idProceso;

    public $monto_total_individual;

    public $pais_marca;

    public $precio_unitario;

    public $cod_asmi;

    public $asmi;

    public $id_ingreso;
    #[Validate]
    public $nro_resolucion_memorandum;
    #[Validate]
    public $nro_orden_compra_servicio;

    public $objeto_recepcion;

    //Todas las variables necesarias para marcar casillas

    public $tiempoEntregaBoolean;
    public $cantidadPedidoBoolean;
    public $calidadSolicitadaBoolean;
    public $terminosReferenciaBoolean;
    public $capacitacionBoolean;
    public $garantiasBoolean;
    public $tiempoEntregaObservaciones;
    public $cantidadPedidoObservaciones;
    public $calidadSolicitadaObservaciones;
    public $terminosReferenciaObservaciones;
    public $capacitacionObservaciones;
    public $garantiasObservaciones;

    public $otrasObservaciones;

    //Fin de las variables

    public function render()
    {
        return view('livewire.almacen-general.ingresos.ingresos-register');
    }

    public function mount()
    {
        $this->fecha_ingreso = Carbon::now()->format('Y-m-d');
        $this->fecha_recepcion = Carbon::now()->format('Y-m-d');
        $this->hora_recepcion = Carbon::now()->format('H:i');
        $this->institucionesExternas = InstitucionExterna::where('estado', true)
            ->where('proveedor_almacen_general', true)
            ->orderBy('razon_social', 'asc')->get();
        $this->procesos = TipoProceso::get();
        $this->materiales = Material::where('estado', true)->get();

        //Inicializar variables para el: Proveedor cumple
        $this->tiempoEntregaBoolean = false;
        $this->cantidadPedidoBoolean = false;
        $this->calidadSolicitadaBoolean = false;
        $this->terminosReferenciaBoolean = false;
        $this->capacitacionBoolean = false;
        $this->garantiasBoolean = false;
    }

    public function rules()
    {
        $reglitas = [
            'cod_ingreso' => 'required',
            'fecha_ingreso' => 'required|date',
            'id_institucion_externa' => 'required',
            'id_tipo_proceso' => 'required',
            'fecha_contrato' => 'required|date',
            'fecha_recepcion' => 'required|date',
            'hora_recepcion' => 'required',
            //'nro_resolucion_memorandum' => 'required',
        ];

        if ($this->id_tipo_proceso) {
            $procesito = TipoProceso::find($this->id_tipo_proceso);

            if ($procesito && $procesito->nombre === 'ANPE') {
                $reglitas['cod_cuce'] = 'required';
            }

            if ($procesito && $procesito->nombre !== 'CONTRATACIÓN MENOR') {
                $reglitas['nro_resolucion_memorandum'] = 'required';
            }
        }

        return $reglitas;
    }

    public function cancelarIngreso()
    {
        return redirect()->route('almacen_general.ingresos.index');
    }

    #[On('agregarFactura')]
    public function agregarFactura()
    {
        $noRepetido = true;

        if ($this->nro_factura === null || trim($this->nro_factura) === '') {
            $noRepetido = false;
            $this->dispatch('sinNumeroFactura');
            $this->skipRender();
        }

        if ($this->fecha_factura === null || trim($this->fecha_factura) === '') {
            $noRepetido = false;
            $this->dispatch('sinFechaFactura');
            $this->skipRender();
        }

        if ($this->monto_total_factura === null || trim($this->monto_total_factura) === '') {
            $noRepetido = false;
            $this->dispatch('sinTotalFactura');
            $this->skipRender();
        }

        $nros_factura = array_column($this->facturaAgregadas, 'nro_factura');

        if (in_array($this->nro_factura, $nros_factura)) {
            $noRepetido = false;
            $this->dispatch('nroFacturaYaExiste');
        }

        if (filter_var($this->nro_factura, FILTER_VALIDATE_INT) === false || $this->nro_factura < 0) {
            $noRepetido = false;
            $this->dispatch('valorNroFacturaNoValido');
            $this->skipRender();
        }

        if (filter_var($this->monto_total_factura, FILTER_VALIDATE_FLOAT) === false || $this->monto_total_factura < 0) {
            $noRepetido = false;
            $this->dispatch('valorMontoTotalFacturaNoValido');
            $this->skipRender();
        }

        if ($noRepetido) {

            $this->monto_total_facturas = $this->monto_total_facturas + $this->monto_total_factura;

            $this->facturaAgregadas[] = [
                'nro_factura' => $this->nro_factura,
                'fecha_factura' => $this->fecha_factura,
                'monto_total_factura' => $this->monto_total_factura,
                'nota_remision' => $this->nota_remision,
            ];
            $this->nro_factura = null;
            $this->fecha_factura = null;
            $this->monto_total_factura = null;
            $this->nota_remision = null;

            $this->dispatch('facturaAgregadaExitosamente');
        }
    }

    public function montosIguales($montoFacturas, $montoMateriales)
    {
        if ($montoFacturas === $montoMateriales) {
            return true;
        } else {
            return false;
        }
    }

    #[On('materialSeleccionado')]
    public function materialSeleccionado()
    {
        if ($this->id_material != null || $this->id_material != '') {
            $nombreMaterial = Material::where('id', $this->id_material)->first();
            $this->nombreMaterial = $nombreMaterial->nombre_material;
            $this->unidadMostrar = $nombreMaterial->unidad;
            $this->descripcionMostrar = $nombreMaterial->descripcion;
        } else {
            $this->nombreMaterial = null;
            $this->unidadMostrar = null;
            $this->descripcionMostrar = null;
        }
    }

    public function calcularMontoTotalIndividual()
    {
        if ($this->cantidad > 0) {
            $this->precio_unitario = round($this->monto_total_individual / $this->cantidad, 2);
        } else {
            $this->precio_unitario = 0;
        }
    }

    public function agregarMaterial()
    {
        $this->validate(
            [
                'id_material' => 'required',
                'nombreMaterial' => 'required',
                'unidadMostrar' => 'required',
                //'fecha_vencimiento' => 'required|date|after:today',
                'monto_total_individual' => 'required| gt:0|max:9',
                'cantidad' => 'required| max:8 | gt:0',
                // 'precio_unitario' => 'gt:0',
                'pais_marca' => 'required',
            ],
            [
                //'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha actual.',
            ]
        );

        $this->calcularMontoTotalIndividual();

        $datosMaterial = Material::where('id', $this->id_material)->first();

        $codigosExistentes = array_column($this->materialesAgregados, 'codigo');
        if (in_array($datosMaterial->codigo, $codigosExistentes)) {
            $this->precio_unitario = null;
            $this->cantidad = null;
            $this->monto_total_individual = 0.00;
            //$monto_individual_total = 0.00;
            $this->fecha_vencimiento = null;
            $this->id_material = null;
            $this->nombreMaterial = null;
            $this->unidadMostrar = null;
            $this->descripcionMostrar = null;

            $this->dispatch('refreshSelect2');
            $this->dispatch('materialDuplicado');
        } else {
            $this->materialesAgregados[] = [
                'id_material' => $datosMaterial->id,
                'codigo' => $datosMaterial->codigo,
                'nombre_material' => $datosMaterial->nombre_material,
                'unidad' => $datosMaterial->unidad,
                'descripcion' => $datosMaterial->descripcion,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'pais_marca' => strtoupper($this->pais_marca),
                'precio_unitario' => $this->precio_unitario,
                'cantidad' => $this->cantidad,
                'total' => $this->monto_total_individual,
            ];

            $this->monto_total = round($this->monto_total + $this->monto_total_individual, 2);
            $this->monto_total_factura = $this->monto_total;

            $this->actualizarListaMateriales();

            $this->precio_unitario = null;
            $this->cantidad = null;
            $this->monto_total_individual = 0.00;
            //$monto_individual_total = 0.00;
            $this->fecha_vencimiento = null;
            $this->id_material = null;
            $this->nombreMaterial = null;
            $this->unidadMostrar = null;
            $this->descripcionMostrar = null;
            $this->pais_marca = null;

            $this->dispatch('refreshSelect2');
            $this->dispatch('materialAgregadoCorrectamente');
        }
    }

    public function actualizarListaMateriales()
    {
        $codigosAgregados = array_map(function ($codsMats) {
            return $codsMats['codigo'];
        }, $this->materialesAgregados);

        $this->materiales = Material::where('estado', true)->whereNotIn('codigo', $codigosAgregados)->get();
    }

    public function tipoProcesoSeleccionado()
    {
        if ($this->id_tipo_proceso != null || $this->id_tipo_proceso != '') {
            $procesoSelected = TipoProceso::where('id', $this->id_tipo_proceso)->first();
            $this->nombreTipoProceso = $procesoSelected->nombre;
        } else {
            $this->nombreTipoProceso = null;
        }
    }

    public function eliminarMaterial($cod_mat, $monto_individual)
    {
        $this->monto_total = round($this->monto_total - $monto_individual, 2);
        $this->monto_total_factura = $this->monto_total;
        $this->materialesAgregados = array_filter($this->materialesAgregados, function ($material) use ($cod_mat) {
            return $material['codigo'] !== $cod_mat;
        });

        $this->materialesAgregados = array_values($this->materialesAgregados);

        if ($this->materialesAgregados == []) {
            $this->monto_total = 0;
            $this->monto_total_factura = $this->monto_total;
        }

        $this->actualizarListaMateriales();
    }

    public function nuevoCodigoAsmi()
    {
        $ingresoAnterior = Ingreso::orderBy('id', 'desc')->first();
        if ($ingresoAnterior) {
            $ultimaGestion = Carbon::parse($ingresoAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->cod_asmi = $ingresoAnterior->codigo_asmi + 1;
                $this->asmi = $this->cod_asmi . '/' . $ultimaGestion;
            } else {
                $ultimaGestion = Carbon::now()->format('Y');
                $this->cod_asmi = 1;
                $this->asmi = $this->cod_asmi . '/' . $ultimaGestion;
            }
        } else {
            $ultimaGestion = Carbon::now()->format('Y');
            $this->cod_asmi = 1;
            $this->asmi = $this->cod_asmi . '/' . $ultimaGestion;
        }
    }

    public function eliminarFactura($codigoFactura)
    {
        foreach ($this->facturaAgregadas as $key => $factura) {
            if ($factura['nro_factura'] === $codigoFactura) {
                $this->monto_total_facturas -= $factura['monto_total_factura'];
                unset($this->facturaAgregadas[$key]);
                break;
            }
        }
        $this->facturaAgregadas = array_values($this->facturaAgregadas);
    }

    public function tratarProveedorCumple()
    {
        if ($this->tiempoEntregaBoolean) {
            if (trim($this->tiempoEntregaObservaciones) === '') {
                $this->tiempoEntregaObservaciones = null;
            }
        } else {
            $this->tiempoEntregaObservaciones = 'NO CORRESPONDE';
        }

        if ($this->cantidadPedidoBoolean) {
            if (trim($this->cantidadPedidoObservaciones) === '') {
                $this->cantidadPedidoObservaciones = null;
            }
        } else {
            $this->cantidadPedidoObservaciones = 'NO CORRESPONDE';
        }

        if ($this->calidadSolicitadaBoolean) {
            if (trim($this->calidadSolicitadaObservaciones) === '') {
                $this->calidadSolicitadaObservaciones = null;
            }
        } else {
            $this->calidadSolicitadaObservaciones = 'NO CORRESPONDE';
        }

        if ($this->terminosReferenciaBoolean) {
            if (trim($this->terminosReferenciaObservaciones) === '') {
                $this->terminosReferenciaObservaciones = null;
            }
        } else {
            $this->terminosReferenciaObservaciones = 'NO CORRESPONDE';
        }

        if ($this->capacitacionBoolean) {
            if (trim($this->capacitacionObservaciones) === '') {
                $this->capacitacionObservaciones = null;
            }
        } else {
            $this->capacitacionObservaciones = 'NO CORRESPONDE';
        }

        if ($this->garantiasBoolean) {
            if (trim($this->garantiasObservaciones) === '') {
                $this->garantiasObservaciones = null;
            }
        } else {
            $this->garantiasObservaciones = 'NO CORRESPONDE';
        }

        if (trim($this->otrasObservaciones) == '') {
            $this->otrasObservaciones = null;
        }
    }

    public function registrarProveedorCumple($idIngreso)
    {

        $this->tratarProveedorCumple();

        $proveedorCumple = ProveedorCumple::create([
            'tiempo_entrega' => $this->tiempoEntregaBoolean,
            'cantidad_pedido' => $this->cantidadPedidoBoolean,
            'calidad_solicitada' => $this->calidadSolicitadaBoolean,
            'terminos_referencia' => $this->terminosReferenciaBoolean,
            'capacitacion' => $this->capacitacionBoolean,
            'garantias' => $this->garantiasBoolean,
            'tiempo_entrega_obs' => $this->tiempoEntregaObservaciones,
            'cantidad_pedido_obs' => $this->cantidadPedidoObservaciones,
            'calidad_solicitada_obs' => $this->calidadSolicitadaObservaciones,
            'terminos_referencia_obs' => $this->terminosReferenciaObservaciones,
            'capacitacion_obs' => $this->capacitacionObservaciones,
            'garantias_obs' => $this->garantiasObservaciones,
            'otras_observaciones' => $this->otrasObservaciones,
            'id_ingreso' => $idIngreso,
            'id_user_created' => auth()->user()->id
        ]);
    }

    #[On('registrarIngreso')]
    public function registrarIngreso($texto)
    {
        //dd('prueba de que entra');
        if ($this->id_institucion_externa === null) {
            $this->dispatch('sinProveedorAgregado');

            return;
        }

        if ($this->facturaAgregadas !== []) {

            if ($this->materialesAgregados !== []) {

                if ($this->montosIguales($this->monto_total_facturas, $this->monto_total)) {
                    $this->validate();
                    $fechaHoraRecepcion = $this->fecha_recepcion . ' ' . $this->hora_recepcion;
                    $datoInstitucionExterna = InstitucionExterna::find($this->id_institucion_externa);

                    DB::beginTransaction();
                    try {
                        //dd('prueba de que entra');
                        $nuevoIngreso = Ingreso::create([
                            'fecha_ingreso' => Carbon::now(),
                            'fecha_contrato' => $this->fecha_contrato,
                            'fecha_recepcion' => Carbon::parse($fechaHoraRecepcion),
                            'hora_recepcion' => $this->hora_recepcion,
                            'monto_total' => $this->monto_total,
                            'codigo_cuce' => $this->cod_cuce,
                            'responsables' => $texto,
                            'id_institucion_externa' => $this->id_institucion_externa,
                            'id_tipo_proceso' => $this->id_tipo_proceso,
                            'codigo_ingreso' => $this->cod_ingreso,
                            'estado_validacion' => 'REGISTRADO',
                            'estado' => true,
                            'nro_resolucion_memorandum' => $this->nro_resolucion_memorandum,
                            'nro_orden_compra_servicio' => $this->nro_orden_compra_servicio,
                            'objeto_recepcion' => $this->objeto_recepcion,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        if ($nuevoIngreso) {

                            $cantidadFacturas = count($this->facturaAgregadas);
                            $cantidadFacturasRegistradasExitosamente = 0;
                            //dd($this->facturaAgregadas);
                            foreach ($this->facturaAgregadas as $key => $facturaAgregada) {
                                $nuevaFactura = Factura::create([
                                    'nro_factura' => $facturaAgregada['nro_factura'],
                                    'fecha' => $facturaAgregada['fecha_factura'],
                                    'id_ingreso' => $nuevoIngreso->id,
                                    'id_institucion_externa' => $this->id_institucion_externa,
                                    'nota_remision' => $facturaAgregada['nota_remision'],
                                    'estado' => true,
                                    'monto_total' => $facturaAgregada['monto_total_factura'],
                                    'id_user_created' => auth()->user()->id,
                                ]);

                                if ($nuevaFactura) {
                                    $cantidadFacturasRegistradasExitosamente++;
                                    //$nuevaFacturaCreada = true;
                                }
                            }

                            if ($cantidadFacturas === $cantidadFacturasRegistradasExitosamente) {
                                $nuevaFacturaCreada = true;
                            } else {
                                $nuevaFacturaCreada = false;
                            }

                            $this->id_ingreso = $nuevoIngreso->id;

                            $contadorCorrectos = 0;

                            foreach ($this->materialesAgregados as $key => $materialAgregado) {
                                $nuevoDetalleIngreso = IngresoMaterial::create([
                                    'precio_unitario' => $materialAgregado['precio_unitario'],
                                    'cantidad' => $materialAgregado['cantidad'],
                                    'precio_total' => $materialAgregado['total'],
                                    'id_material' => $materialAgregado['id_material'],
                                    'id_ingreso' => $nuevoIngreso->id,
                                    'fecha_vencimiento' => $materialAgregado['fecha_vencimiento'],
                                    'estado_adelanto' => false,
                                    'pais_marca' => $materialAgregado['pais_marca'],
                                    'id_user_created' => auth()->user()->id,
                                ]);

                                if ($nuevoDetalleIngreso) {
                                    $contadorCorrectos = $contadorCorrectos + 1;
                                }
                            }

                            if ($contadorCorrectos == count($this->materialesAgregados)) {
                                $nuevoDetalleIngresoCreado = true;
                            } else {
                                $nuevoDetalleIngresoCreado = false;
                            }

                            $this->registrarProveedorCumple($nuevoIngreso->id);

                            if ($nuevaFacturaCreada && $nuevoDetalleIngresoCreado) {
                                // inicio de notificacion
                                /*$roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
                                $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                                $gestionCreacion = Carbon::parse($nuevoIngreso->created_at)->format('Y');
                                // aqui
                                $procesoTipo = TipoProceso::where('id', $nuevoIngreso->id_tipo_proceso)->first();
                                $nombreCreador = $nuevoIngreso->usuarioCreador->name;
                                foreach ($receptores as $receptor) {
                                    NotificacionUsuarios::create([
                                        'estado' => 'NO LEIDO',
                                        'prioridades' => 2,
                                        'id_usu_emisor' => auth()->user()->id,
                                        'id_usu_receptor' => $receptor->model_id,
                                        'fecha_hora_enviada' => Carbon::now(),
                                        'asunto' => 'ALMACEN GENERAL - REGISTRO DE NUEVO INGRESO',
                                        'mensaje' => 'Se registró correctamente un nuevo ingreso con código: ' . $procesoTipo->sigla . '/' .
                                            $nuevoIngreso->cod_ingreso . '/' . $gestionCreacion . ', 
                                    siendo un proceso de tipo: ' . $procesoTipo->nombre . '. Con código 
                                    ASMI Nro: ' . $nuevoIngreso->cod_asmi . '/' . $gestionCreacion . '. Con un monto total de Bs. ' . $nuevoIngreso->monto_total
                                            . '. Por el usuario: ' . $nombreCreador,
                                    ]);
                                }*/
                                // fin de notificacion
                                DB::commit();
                                $this->dispatch('guardado');

                                //SE CREA PDF DE NUEVO INGRESO Y SE GUARDA EN FTP
                                $url = route('almacengeneral.ingresosmateriales.pdf', ['modo' => 'create', 'rowId' => $nuevoIngreso->id]);   //AQUI RAYCUSSSSSS
                                $this->js("window.open('{$url}', '_blank');");
                                //$this->js('window.open("/farmacia/almacenfarmacia/ingresos/' . $this->id_ingreso . '", "_blank")');

                                return redirect()->route('almacen_general.ingresos.index');
                            } else {
                                DB::rollBack();
                                $this->dispatch('noGuardado');
                            }
                        } else {
                            DB::rollBack();
                            $this->dispatch('noGuardado');
                        }
                    } catch (\Throwable $th) {
                        dd('que paso?', $th);
                        DB::rollBack();
                        $this->dispatch('noGuardado');
                    }
                } else {
                    $this->dispatch('montosDesiguales');
                }
            } else {
                $this->dispatch('materialesVacio');
            }
        } else {
            $this->dispatch('facturasVacias');
        }
    }

    //////////////////////AUN NO MODIFICADOS

    /*public function nuevoNumeroLote()
    {
        $ingresoAnterior = Ingreso::orderBy('id', 'desc')->first();
        if ($ingresoAnterior) {
            $ultimaGestion = Carbon::parse($ingresoAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->nro_lote = $ingresoAnterior->nro_lote + 1;
            } else {
                $this->nro_lote = 1;
            }
        } else {
            $ultimaGestion = Carbon::now()->format('Y');
            $this->nro_lote = 1;
        }
    }*/

    public function cancelar()
    {
        return redirect()->route('almacen_general.ingresos.index');
    }

    public function atras()
    {
        return redirect()->route('almacen_general.ingresos.index');
    }
}
