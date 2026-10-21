<?php

namespace App\Livewire\AlmacenGeneral\Ingresos;

use App\Models\AlmacenGeneral\Factura;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\IngresoMaterial;
use App\Models\AlmacenGeneral\MaterialStock;
use App\Models\AlmacenGeneral\Movimiento;
use App\Models\AlmacenGeneral\ProveedorCumple;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class IngresosMain extends Component
{
    public $idIngreso;

    public $proveedor;

    public $nro_lote;

    public $cod_asmi;

    public $asmi;

    public $monto_total;

    public $nro_ingreso;

    public $fecha_ingreso, $fecha_ingreso_mostrar;

    public $tipo_proceso;

    public $nro_factura;

    public $fecha_factura;

    public $total_factura;

    public $nro_ingreso_literal;

    public $cod_asmi_literal;

    public $fecha_contrato;

    public $fecha_recepcion;

    public $hora_recepcion;

    public $cod_cuce;

    public $responsables;

    public $facturas;

    public $listaMateriales = [];

    public $materialesIngreso = [];

    public $idAlmacenGeneral;
    public $materialesIngresoArray, $nro_ingreso_literal_editar, $tipo_proceso_editar, $fecha_min_mes;

    public $nro_resolucion_memorandum;

    public $objeto_recepcion;

    //VARIABLES DE PROVEEDOR CUMPLE

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
        return view('livewire.almacen-general.ingresos.ingresos-main');
    }

    public function registrarIngreso()
    {
        return redirect()->route('almacen_general.ingresos.create');
    }

    public function mount()
    {
        $this->idAlmacenGeneral = 19;
    }

    #[On('verIngresoMateriales')]
    public function verIngresoMateriales($data)
    {
        $this->idIngreso = $data;
        $this->reseteeeeo();
        $this->mostrarIngresoMaterial();
        $this->dispatch('modalVerIngresoMateriales');
    }

    public function mostrarIngresoMaterial()
    {
        if ($this->idIngreso) {
            $ingreso = Ingreso::where('id', $this->idIngreso)->with([
                'procesoIngreso',
                'institucionExternaIngreso',
            ])->first();
            $this->cod_asmi = $ingreso->codigo_asmi;
            $this->cod_asmi_literal = $ingreso->codigo_asmi . '/' . Carbon::parse($ingreso->created_at)->format('Y');
            $montoTotal = $ingreso->monto_total;
            $this->monto_total = number_format($montoTotal, 2, ',', '.');
            $this->nro_ingreso = $ingreso->codigo_ingreso;
            //$this->nro_ingreso_literal = $ingreso->procesoIngreso->sigla . '/' . $this->nro_ingreso . '/' . Carbon::parse($ingreso->created_at)->format('Y');
            $this->nro_ingreso_literal = $this->nro_ingreso . '/' . Carbon::parse($ingreso->created_at)->format('Y');
            $fecha = $ingreso->fecha_ingreso;
            $this->fecha_ingreso = Carbon::parse($fecha)->format('Y-m-d');
            // $this->fecha_ingreso_mostrar = Carbon::parse($fecha)->format('d/m/Y');
            $this->fecha_contrato = $ingreso->fecha_contrato;
            $this->fecha_recepcion = Carbon::parse($ingreso->fecha_recepcion)->format('Y-m-d');
            $this->hora_recepcion = Carbon::parse($ingreso->hora_recepcion)->format('H:i');
            $this->proveedor = $ingreso->institucionExternaIngreso->razon_social;
            $this->tipo_proceso = $ingreso->procesoIngreso->nombre;

            $this->nro_resolucion_memorandum = $ingreso->nro_resolucion_memorandum;

            $this->objeto_recepcion = $ingreso->objeto_recepcion;

            $this->cod_cuce = $ingreso->codigo_cuce ?: 'NO REGISTRADO';
            $this->responsables = $ingreso->responsables;

            $factura_select = Factura::where('id_ingreso', $this->idIngreso)
                ->where('estado', true)->get();
            foreach ($factura_select as $factura) {
                $this->facturas[] = [
                    'nro_factura' => $factura->nro_factura ?? 'SIN DATO',
                    'fecha_factura' => $factura->fecha,
                    'total_factura' => $factura->monto_total ?? 'SIN DATO',
                    'tipo' => $factura->nota_remision ? 'NOTA REMISIÓN' : 'FACTURA',

                ];
            }
            // dd($this->facturas);

            $this->materialesIngreso = IngresoMaterial::where('id_ingreso', $ingreso->id)
                ->with('materialIngresoMaterial')
                ->get();
            //dd($this->materialesIngreso);


            //VARIABLES DE PROVEEDOR CUMPLE
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
        }
    }

    public function nuevoCodigoAsmi()
    {
        $ingresoAnterior = Ingreso::orderBy('id', 'desc')->where('codigo_asmi', '!=', null)->first();
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

    #[On('validarIngresoMateriales')]
    public function validarIngresoMateriales($data)
    {
        $this->idIngreso = $data;
        $ingresoExiste = Ingreso::find($data);
        if ($ingresoExiste) {

            $this->dispatch('validarIngresoAlmacenGeneral');
        } else {
            $this->dispatch('noExisteIngresoError');
        }
    }

    #[On('registrarIngresoMateriales')]
    public function registrarIngresoMateriales()
    {
        //dd('que cagada');
        DB::beginTransaction();
        try {
            $ingresoAValidar = Ingreso::with(['institucionExternaIngreso'])->find($this->idIngreso);
            $this->nuevoCodigoAsmi();
            // dd($this->cod_asmi);
            //dd($ingresoAValidar);

            if ($ingresoAValidar) {
                $detallesIngresoAValidar = IngresoMaterial::where('id_ingreso', $ingresoAValidar->id)->get();
                if (count($detallesIngresoAValidar) > 0) {

                    foreach ($detallesIngresoAValidar as $key => $detalleIngresoAValidar) {
                        //INSERCION A LA TABLA MEDICAMENTOS STOCK

                        $maxNroLote = MaterialStock::where('id_material', $detalleIngresoAValidar->id_material)
                            ->where('id_unidad', $this->idAlmacenGeneral)
                            ->max('nro_lote');

                        $nroLotePorMaterial = $maxNroLote ? $maxNroLote + 1 : 1;

                        $nuevoMaterialoStock = MaterialStock::create([
                            'cantidad_inicial' => $detalleIngresoAValidar->cantidad,
                            'precio_unitario' => $detalleIngresoAValidar->precio_unitario,
                            'stock_actualizado' => $detalleIngresoAValidar->cantidad,
                            'nro_lote' => $nroLotePorMaterial,
                            'id_material' => $detalleIngresoAValidar->id_material,
                            'id_unidad' => $this->idAlmacenGeneral,
                            'fecha_vencimiento' => $detalleIngresoAValidar->fecha_vencimiento,
                            'ajuste_contable' => false,
                            'id_user_created' => auth()->user()->id,
                            'id_ingreso' => $this->idIngreso,
                        ]);

                        // FIN DE LA INSERCION EN LA TABLA MEDICAMENTOS STOCK -----------------------------------------------------

                        //PARA LA TABLA MOVIMIENTO

                        /*$movimientoPrevio = Movimiento::where('id_material', $detalleIngresoAValidar->id_material)
                            ->where('id_unidad', $this->idAlmacenGeneral)
                            //->where('nro_lote', $this->nro_lote)
                            ->orderBy('fecha', 'desc')
                            ->first();*/

                        $saldoMatStock = MaterialStock::where('id_material', $detalleIngresoAValidar->id_material)
                            ->where('id_unidad', $this->idAlmacenGeneral)
                            ->sum('stock_actualizado');

                        $saldo = 0;

                        if ($saldoMatStock) {
                            //DADO QUE ES UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                            $saldo = $saldoMatStock;
                        } else {
                            $saldo = $detalleIngresoAValidar->cantidad;
                        }

                        $nuevoMovimiento = Movimiento::create([
                            'id_corresponde' => $ingresoAValidar->id,
                            'id_identificador_tabla' => 1,
                            'dato_solicitante' => 'ALMACEN GENERAL',
                            'nro_documento' => $ingresoAValidar->codigo_ingreso,
                            'fecha' => $ingresoAValidar->fecha_ingreso,
                            'dato_interesado' => $ingresoAValidar->institucionExternaIngreso->razon_social,
                            'cantidad_ingreso' => $detalleIngresoAValidar->cantidad,
                            'cantidad_salida' => 0,
                            'saldo' => $saldo,
                            'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                            'id_estado_validacion' => 5,
                            'id_material' => $detalleIngresoAValidar->id_material,
                            'id_unidad' => $this->idAlmacenGeneral,
                            'nro_lote' => $nroLotePorMaterial,
                            'precio_unitario_peps' => $detalleIngresoAValidar->precio_unitario,
                            'fecha_vencimiento' => $detalleIngresoAValidar->fecha_vencimiento,
                            'id_user_created' => auth()->user()->id,
                            'stock_real' => true,
                        ]);

                        //FIN DE LA TABLA MOVIMIENTOS ----------------------------------------------------------------------
                    }
                }
                $ingresoAValidar->estado_validacion = 'VALIDADO';
                $ingresoAValidar->codigo_asmi = $this->cod_asmi;
                $ingresoAValidar->id_user_valida = auth()->user()->id;
                $ingresoAValidar->fecha_valida = Carbon::now();
                $ingresoAValidar->save();
                DB::commit();
                $this->dispatch('ingresoValidadoCorrectamente');
                $this->dispatch('pg:eventRefresh-IngresosAlmacenGeneralTable');
                $this->idIngreso = null;
            } else {
                DB::rollBack();
                $this->idIngreso = null;
                $this->dispatch('noExisteIngresoError');
            }
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            $this->idIngreso = null;
            $this->dispatch('noExisteIngresoError');
        }
    }

    public function reseteeeeo()
    {
        $this->reset(
            'facturas',
            'materialesIngreso',
            'nro_lote',
            'cod_asmi_literal',
            'monto_total',
            'nro_ingreso_literal',
            'fecha_ingreso',
            'fecha_contrato',
            'fecha_recepcion',
            'hora_recepcion',
            'proveedor',
            'tipo_proceso',
            'responsables'
        );
    }

    #[On('verPDF')]
    public function verPDF($rowId)
    {
        $url = route('almacengeneral.ingresosmateriales.pdf', ['modo' => 'ver', 'rowId' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");
    }
}
