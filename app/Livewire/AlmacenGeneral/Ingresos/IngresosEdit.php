<?php

namespace App\Livewire\AlmacenGeneral\Ingresos;

use App\Models\AlmacenGeneral\Factura;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\IngresoMaterial;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\ProveedorCumple;
use App\Models\Farmacia\InstitucionExterna;
use App\Models\Farmacia\TipoProceso;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class IngresosEdit extends Component
{
    public $idIngreso;

    //ATRIBUTOS DE LA TABLA INGRESO
    public $institucionesExternas = [];

    public $procesos;

    #[Validate]
    public $cod_ingreso;

    #[Validate]
    public $fecha_ingreso;

    public $nro_lote;

    public $cod_asmi;

    public $asmi;

    public $monto_total;

    #[Validate]
    public $id_institucion_externa;

    public $nombreIntitucionExterna;

    #[Validate]
    public $id_tipo_proceso;

    public $nombreTipoProceso;

    #[Validate]
    public $fecha_contrato;

    #[Validate]
    public $fecha_recepcion;

    #[Validate]
    public $hora_recepcion;

    //ATRIBUTOS DE LA TABLA DETALLE INGRESO
    public $precio_unitario;

    public $cantidad;

    public $precio_total;

    public $id_material;

    public $id_ingreso;

    public $fecha_vencimiento;

    public $estado_adelanto;

    //ATRIBUTOS DE LA TABLA FACTURA
    #[Validate]
    public $nro_factura;

    #[Validate]
    public $fecha_factura;

    //public $id_ingreso;
    //public $id_institucion_externa;
    public $monto_total_factura;

    //TABLA MATERIALES
    public $materiales;

    public $nombreMaterial;

    public $unidadMostrar;

    public $descripcionMostrar;

    public $responsables;

    public $cod_cuce;

    public $materialesAgregadosOriginales;

    public $materialesAgregados = [];

    public $facturaAgregadas = [];

    public $monto_total_facturas = 0.00;

    public $pais_marca;

    public $nota_remision = false;

    public $monto_total_individual;

    public $facturasOriginales;

    public $idUserCreatedIngreso;

    #[Validate]
    public $nro_resolucion_memorandum;

    public $objeto_recepcion;


    //PARA LA PARTE DE LAS CASILLAS DE LO QUE CUMPLE EL PROVEEDOR

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

    public function render()
    {
        return view('livewire.almacen-general.ingresos.ingresos-edit');
    }

    public function mount()
    {
        $this->institucionesExternas = InstitucionExterna::all();
        $this->procesos = TipoProceso::get();
        $this->materiales = Material::where('estado', true)->get();
        $this->mostrarIngreso($this->idIngreso);
        $this->dispatch('cargarIdInstitucionExterna', id: $this->id_institucion_externa);
    }

    public function rules()
    {
        $reglitas =  [
            'cod_ingreso' => 'required',
            'fecha_ingreso' => 'required|date',
            'id_institucion_externa' => 'required',
            'id_tipo_proceso' => 'required',
            'fecha_contrato' => 'required|date',
            'fecha_recepcion' => 'required|date',
            'hora_recepcion' => 'required',
        ];

        if ($this->id_tipo_proceso) {
            $procesito = TipoProceso::find($this->id_tipo_proceso);

            if ($procesito && $procesito->nombre === 'ANPE') {
                $reglitas['cod_cuce'] = 'required';
            }

            if ($procesito && $procesito->nombre !== 'CONTRATACIÓN MENOR'){
                $reglitas['nro_resolucion_memorandum'] = 'required';
            }
        }

        return $reglitas;
    }

    public function mostrarIngreso($idIngreso)
    {
        $ingreso = Ingreso::where('id', $idIngreso)
            ->with([
                'usuarioCreador',
                'usuarioCreador.personaUser',
                'institucionExternaIngreso',
                'procesoIngreso',
            ])
            ->first();

        $this->id_institucion_externa = $ingreso->id_institucion_externa;
        $this->nombreIntitucionExterna = $ingreso->institucionExternaIngreso->razon_social;
        $this->id_tipo_proceso = $ingreso->id_tipo_proceso;
        $this->nombreTipoProceso = $ingreso->procesoIngreso->nombre;
        $this->fecha_ingreso = Carbon::parse($ingreso->fecha_ingreso)->format('Y-m-d');
        $this->fecha_contrato = Carbon::parse($ingreso->fecha_contrato)->format('Y-m-d');
        $this->fecha_recepcion = Carbon::parse($ingreso->fecha_recepcion)->format('Y-m-d');
        $this->hora_recepcion = Carbon::parse($ingreso->hora_recepcion)->format('H:i:s');
        $this->cod_ingreso = $ingreso->codigo_ingreso;
        $this->cod_asmi = $ingreso->codigo_asmi;
        $this->asmi = $ingreso->cod_asmi . '/' . Carbon::parse($ingreso->created_at)->format('Y');
        $this->nro_lote = $ingreso->nro_lote;
        $this->monto_total = $ingreso->monto_total;
        $this->responsables = $ingreso->responsables;
        $this->cod_cuce = $ingreso->codigo_cuce;
        $this->idUserCreatedIngreso = $ingreso->id_user_created;
        $this->objeto_recepcion = $ingreso->objeto_recepcion;
        $this->nro_resolucion_memorandum = $ingreso->nro_resolucion_memorandum;

        $proveedorCumple = ProveedorCumple::where('id_ingreso', $ingreso->id)->first();

        $this->tiempoEntregaBoolean = $proveedorCumple ? $proveedorCumple->tiempo_entrega : false;
        $this->cantidadPedidoBoolean = $proveedorCumple ? $proveedorCumple->cantidad_pedido : false;
        $this->calidadSolicitadaBoolean = $proveedorCumple ? $proveedorCumple->calidad_solicitada : false;
        $this->terminosReferenciaBoolean = $proveedorCumple ? $proveedorCumple->terminos_referencia : false;
        $this->capacitacionBoolean = $proveedorCumple ? $proveedorCumple->capacitacion : false;
        $this->garantiasBoolean = $proveedorCumple ? $proveedorCumple->garantias : false;
        $this->tiempoEntregaObservaciones = $proveedorCumple ? $proveedorCumple->tiempo_entrega_obs : null;
        $this->cantidadPedidoObservaciones = $proveedorCumple ? $proveedorCumple->cantidad_pedido_obs : null;
        $this->calidadSolicitadaObservaciones = $proveedorCumple ? $proveedorCumple->calidad_solicitada_obs : null;
        $this->terminosReferenciaObservaciones = $proveedorCumple ? $proveedorCumple->terminos_referencia_obs : null;
        $this->capacitacionObservaciones = $proveedorCumple ? $proveedorCumple->capacitacion_obs : null;
        $this->garantiasObservaciones = $proveedorCumple ? $proveedorCumple->garantias_obs : null;
        $this->otrasObservaciones = $proveedorCumple ? $proveedorCumple->otras_observaciones : null;

        $listaMateriales = IngresoMaterial::where('id_ingreso', $idIngreso)->with('materialIngresoMaterial')->get();
        //dd($listaMateriales->ingresoIngresoMaterial);
        foreach ($listaMateriales as $key => $datosMaterial) {
            $this->materialesAgregados[] = [
                'id_material' => $datosMaterial->materialIngresoMaterial->id,
                'codigo' => $datosMaterial->materialIngresoMaterial->codigo,
                'nombre_material' => $datosMaterial->materialIngresoMaterial->nombre_material,
                'unidad' => $datosMaterial->materialIngresoMaterial->unidad,
                'descripcion' => $datosMaterial->materialIngresoMaterial->descripcion,
                'fecha_vencimiento' => $datosMaterial->fecha_vencimiento,
                'precio_unitario' => $datosMaterial->precio_unitario,
                'cantidad' => $datosMaterial->cantidad,
                'pais_marca' => $datosMaterial->pais_marca,
                'total' => $datosMaterial->precio_total,
            ];
        }
        //dd($this->materialesAgregados);

        $this->materialesAgregadosOriginales = $this->materialesAgregados;

        $listaFacturas = Factura::where('id_ingreso', $idIngreso)->where('estado', true)->get();
        foreach ($listaFacturas as $key => $datosFactura) {
            $this->monto_total_facturas = $this->monto_total_facturas + $datosFactura->monto_total;
            $this->facturaAgregadas[] = [
                'id_factura' => $datosFactura->id,
                'nro_factura' => $datosFactura->nro_factura,
                'fecha_factura' => $datosFactura->fecha,
                'monto_total_factura' => $datosFactura->monto_total,
                'nota_remision' => $datosFactura->nota_remision,
            ];
        }
        $this->facturasOriginales = $this->facturaAgregadas;
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

    public function eliminarFactura($codigoFactura)
    {
        //dd('entra o no');
        $codigoFactura = (int) $codigoFactura;
        foreach ($this->facturaAgregadas as $key => $factura) {
            //dd($factura['nro_factura'], $codigoFactura);
            if ($factura['nro_factura'] === $codigoFactura) {

                $this->monto_total_facturas -= $factura['monto_total_factura'];
                unset($this->facturaAgregadas[$key]);
                break;
            }
        }
        $this->facturaAgregadas = array_values($this->facturaAgregadas);
        $this->dispatch('facturaEliminadaCorrectamente');
    }

    #[On('materialSeleccionado')]
    public function materialSeleccionado()
    {
        if ($this->id_material != null || $this->id_material != '') {
            $nombreMat = Material::where('id', $this->id_material)->first();
            $this->nombreMaterial = $nombreMat->nombre_material;
            $this->unidadMostrar = $nombreMat->unidad;
            $this->descripcionMostrar = $nombreMat->descripcion;
        } else {
            $this->nombreMaterial = null;
            $this->descripcionMostrar = null;
            $this->unidadMostrar = null;
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
            $this->skipRender();
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

        /*if (Factura::where('nro_factura', $this->nro_factura)
            ->where('id_institucion_externa', $this->id_institucion_externa)
            ->exists()
        ) {
            $noRepetido = false;
            $this->dispatch('nroFacturaYaExiste');
            $this->skipRender();
        }*/

        if ($noRepetido) {
            $this->monto_total_facturas = $this->monto_total_facturas + $this->monto_total_factura;

            $this->facturaAgregadas[] = [
                'id_factura' => null,
                'nro_factura' => $this->nro_factura,
                'fecha_factura' => $this->fecha_factura,
                'monto_total_factura' => $this->monto_total_factura,
                'nota_remision' => $this->nota_remision,
            ];

            //dd($this->facturaAgregadas);
            // dd($this->facturaAgregadas);
            $this->nro_factura = null;
            $this->fecha_factura = null;
            $this->monto_total_factura = null;
            $this->nota_remision = false;

            $this->dispatch('facturaAgregadaExitosamente');
        }
        //dd($this->facturaAgregadas);
    }

    public function montosIguales($montoFacturas, $montoMateriales)
    {
        //dd($montoFacturas, $montoMateriales);
        if ((int) $montoFacturas === (int) $montoMateriales) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarMaterial($cod_mat, $monto_individual)
    {
        //dd('entra locoooo');
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
        $this->dispatch('materialEliminadoCorrectamente');
    }

    public function actualizarListaMateriales()
    {
        $codigosAgregados = array_map(function ($codsMats) {
            return $codsMats['codigo'];
        }, $this->materialesAgregados);

        $this->materiales = Material::where('estado', true)->whereNotIn('codigo', $codigosAgregados)->get();
    }

    public function calcularTotal()
    {
        $this->monto_total = 0;
        $this->monto_total_factura = 0;

        foreach ($this->materialesAgregados as $index => $material) {
            $total = floatval($material['total'] ?? 0);
            $cantidad = floatval($material['cantidad'] ?? 0);

            if ($total == 0 || $cantidad == 0) {
                $this->materialesAgregados[$index]['precio_unitario'] = 0;
                //$this->dispatch('valoresVacios');
            }

            $precioUnitario = $total / $cantidad;
            $this->materialesAgregados[$index]['precio_unitario'] = round($precioUnitario, 2);
            $this->monto_total += $total;
            $this->monto_total_factura += $total;
        }
    }

    /*public function obtenerNombreAlmacen()
    {
        $datosUnidades = new ListaUnidades();

        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['nombre'];
    }*/

    public function obtenerIdAlmacenGeneral()
    {
        /*$datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];*/
        return 19;
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

    public function editarProveedorCumple($idIngreso)
    {

        $this->tratarProveedorCumple();

        $proveedorEditar = ProveedorCumple::where('id_ingreso', $idIngreso)->first();

        if ($proveedorEditar) {
            $proveedorEditar->update([
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
                'id_user_updated' => auth()->user()->id
            ]);
        }
    }

    #[On('editarIngresoMaterial')]
    public function editarIngresoMaterial($texto)
    {
        if ($this->id_institucion_externa === null) {
            $this->dispatch('sinProveedorAgregado');

            return;
        }

        if ($this->facturaAgregadas !== []) {
            if ($this->materialesAgregados != []) {
                if ($this->montosIguales($this->monto_total_facturas, $this->monto_total)) {
                    $this->validate();
                    DB::beginTransaction();
                    try {
                        $fechaHoraRecepcion = $this->fecha_recepcion . ' ' . $this->hora_recepcion;
                        $ingresoEditado = Ingreso::find($this->idIngreso);
                        $ingresoEditado->id_institucion_externa = $this->id_institucion_externa;
                        $ingresoEditado->fecha_ingreso = $this->fecha_ingreso;
                        $ingresoEditado->monto_total = $this->monto_total;
                        $ingresoEditado->id_tipo_proceso = $this->id_tipo_proceso;
                        $ingresoEditado->codigo_ingreso = $this->cod_ingreso;
                        $ingresoEditado->codigo_cuce = $this->cod_cuce;
                        $ingresoEditado->responsables = $texto;
                        $ingresoEditado->fecha_recepcion = Carbon::parse($fechaHoraRecepcion);
                        $ingresoEditado->hora_recepcion = $this->hora_recepcion;
                        $ingresoEditado->fecha_contrato = $this->fecha_contrato;
                        $ingresoEditado->objeto_recepcion = $this->objeto_recepcion;
                        $ingresoEditado->nro_resolucion_memorandum = $this->nro_resolucion_memorandum;
                        $ingresoEditado->id_user_updated = auth()->user()->id;

                        $ingresoEditado->save();

                        //PARA LA EDICION DE LOS DATOS DE LA FACTURA LOCOOOOOO

                        foreach ($this->facturasOriginales as $facturaOriginal) {
                            $factura = Factura::where('id_ingreso', $this->idIngreso)
                                ->where('id', $facturaOriginal['id_factura'])
                                ->first();

                            if ($factura) {
                                $factura->estado = false;
                                $factura->id_user_updated = auth()->user()->id;
                                $factura->save();
                            }
                        }

                        foreach ($this->facturaAgregadas as $facturaNueva) {
                            $nuevaFacturaEditada = Factura::create([
                                'nro_factura' => $facturaNueva['nro_factura'],
                                'fecha' => $facturaNueva['fecha_factura'],
                                'id_ingreso' => $this->idIngreso,
                                'id_institucion_externa' => $this->id_institucion_externa,
                                'monto_total' => $facturaNueva['monto_total_factura'],
                                'nota_remision' => $facturaNueva['nota_remision'],
                                'estado' => true,
                                'id_user_created' => auth()->user()->id,
                                'id_user_updated' => auth()->user()->id,
                            ]);
                        }

                        //FIN DE LA EDICION DE FACTURAS

                        //EDICION DE MATERIALES CHEEEE

                        foreach ($this->materialesAgregadosOriginales as $key => $materialAgregadoOriginal) {

                            $detalleIngreso = IngresoMaterial::where('id_ingreso', $this->idIngreso)
                                ->where('id_material', $materialAgregadoOriginal['id_material'])
                                ->first();

                            //TRATAMIENTO DE LA TABLA DETALLE MOVIMIENTO: MATEN LA TABLA, salvaguarden EL ID USER CREATED!!! ESTO YA NO FUNCIONA PANITAAA ATTE. YO MISMO

                            $detalleIngreso->delete();
                        }

                        foreach ($this->materialesAgregados as $materialAgregado) {
                            $nuevoDetalleIngreso = IngresoMaterial::create([
                                'precio_unitario' => $materialAgregado['precio_unitario'],
                                'cantidad' => $materialAgregado['cantidad'],
                                'precio_total' => $materialAgregado['total'],
                                'id_material' => $materialAgregado['id_material'],
                                'id_ingreso' => $this->idIngreso,
                                'nro_lote' => $this->nro_lote,
                                'id_user_created' => $this->idUserCreatedIngreso,
                                'id_user_updated' => auth()->user()->id,
                                'pais_marca' => $materialAgregado['pais_marca'],
                                'fecha_vencimiento' => $materialAgregado['fecha_vencimiento'],
                            ]);
                        }

                        //FIN DE LA EDICION DE MATERIALES

                        // INICIO DE LA EDICION DE PROVEEDOR CUMPLE

                        $this->editarProveedorCumple($ingresoEditado->id);    

                        // FIN DE LA EDICION DE PROVEEDOR CUMPLE

                        DB::commit();
                        $this->dispatch('guardado');

                        //PDF EDITADO DE INGRESO Y SE GUARDA EN FTP
                        $url = route('almacengeneral.ingresosmateriales.pdf', ['modo' => 'edit', 'rowId' => $ingresoEditado->id]);
                        $this->js("window.open('{$url}', '_blank');");

                        return redirect()->route('almacen_general.ingresos.index');
                    } catch (\Throwable $th) {
                        dd($th);
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

    public function agregarMaterial()
    {
        $this->validate(
            [
                'id_material' => 'required',
                'nombreMaterial' => 'required',
                'unidadMostrar' => 'required',
                //'fecha_vencimiento' => 'required| date',
                'monto_total_individual' => 'required| gt:0|max:9',
                'cantidad' => 'required| max:8 | gt:0',
                // 'precio_unitario' => 'gt:0',
                'pais_marca' => 'required',
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

            $this->dispatch('refreshSelect2');
            $this->dispatch('materialDuplicado');
        } else {
            // dd($this->precio_unitario , $this->cantidad, $this->monto_total_individual);
            //$monto_individual_total = $this->precio_unitario * $this->cantidad;
            //dd($monto_individual_total);
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
            $this->pais_marca = null;

            $this->dispatch('refreshSelect2');
            $this->dispatch('materialAgregadoCorrectamente');
        }
    }

    public function cancelar()
    {
        return redirect()->route('almacen_general.ingresos.index');
    }

    public function atras()
    {
        return redirect()->route('almacen_general.ingresos.index');
    }
}
