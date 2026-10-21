<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\IngresoMedicamentos;

use App\Models\Farmacia\DetalleIngreso;
use App\Models\Farmacia\Factura;
use App\Models\Farmacia\Ingreso;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Services\ListaUnidades;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class IngresoMedicamentosMain extends Component
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

    public $listaMedicamentos = [];

    public $medicamentosIngreso = [];

    public $idAlmacenFarmacia;
    public $medicamentosIngresoArray, $nro_ingreso_literal_editar, $tipo_proceso_editar, $fecha_min_mes;


    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.ingreso-medicamentos-main');
    }

    public function mount()
    {
        $this->idAlmacenFarmacia = $this->obtenerIdAlmacen();
    }

    public function registrar_ingreso()
    {
        return redirect()->route('almacen_farmacia.ingresos.create');
    }

    #[On('editarFechasVencimieno')]
    public function editarFechasVencimieno($data)
    {
        // dd('sdsa');
        // $this->idIngreso = $data;
        // $this->reseteeeeo();
        // $this->mostrarIngresoMedicamento();
        $this->fecha_min_mes =  now()->endOfMonth()->format('Y-m-d');
        //dd($this->fecha_min_mes);
        $this->mostrarEnEditar($data);
        $this->dispatch('modalEditarFechaVencimiento');
    }

    #[On('verIngresoMedicamentos')]
    public function verIngresoMedicamentos($data)
    {
        $this->idIngreso = $data;
        $this->reseteeeeo();
        $this->mostrarIngresoMedicamento();
        $this->dispatch('modalVerIngresoMedicamentos');
    }

    #[On('verPDF')]
    public function verPDF($rowId)
    {
        $url = route('farmacia.almacenfarmacia.ingresos.pdf', ['modo' => 'ver', 'rowId' => $rowId]);
        $this->js("window.open('{$url}', '_blank');");
    }

    private static function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    #[On('validarIngresoMedicamentos')]
    public function validarIngresoMedicamentos($data)
    {
        $this->idIngreso = $data;
        $ingresoExiste = Ingreso::find($data);
        if ($ingresoExiste) {

            $this->dispatch('validarIngresoAlmacen');
        } else {
            $this->dispatch('noExisteIngresoError');
        }
    }

    #[On('registrarIngresoMedicamentos')]
    public function registrarIngresoMedicamentos()
    {
        DB::beginTransaction();
        try {
            $ingresoAValidar = Ingreso::with(['institucionExternaIngreso'])->find($this->idIngreso);
            $this->nuevoCodigoAsmi();
            // dd($this->cod_asmi);

            if ($ingresoAValidar) {
                $detallesIngresoAValidar = DetalleIngreso::where('id_ingreso', $ingresoAValidar->id)->get();
                if (count($detallesIngresoAValidar) > 0) {

                    foreach ($detallesIngresoAValidar as $key => $detalleIngresoAValidar) {
                        //INSERCION A LA TABLA MEDICAMENTOS STOCK

                        $maxNroLote = MedicamentoStock::where('id_medicamento', $detalleIngresoAValidar->id_medicamento)
                            ->where('id_unidad', $this->idAlmacenFarmacia)
                            ->max('nro_lote');

                        $nroLotePorMedicamento = $maxNroLote ? $maxNroLote + 1 : 1;

                        $nuevoMedicamentoStock = MedicamentoStock::create([
                            'cantidad_inicial' => $detalleIngresoAValidar->cantidad,
                            'precio_unitario' => $detalleIngresoAValidar->precio_unitario,
                            'stock_actualizado' => $detalleIngresoAValidar->cantidad,
                            'nro_lote' => $nroLotePorMedicamento,
                            'id_medicamento' => $detalleIngresoAValidar->id_medicamento,
                            'id_unidad' => $this->idAlmacenFarmacia,
                            'fecha_vencimiento' => $detalleIngresoAValidar->fecha_vencimiento,
                            'ajuste_contable' => false,
                            'id_user_created' => auth()->user()->id,
                            'id_ingreso' => $this->idIngreso,
                        ]);

                        // FIN DE LA INSERCION EN LA TABLA MEDICAMENTOS STOCK -----------------------------------------------------

                        //PARA LA TABLA MOVIMIENTO

                        /*$movimientoPrevio = Movimiento::where('id_medicamento', $detalleIngresoAValidar->id_medicamento)
                            ->where('id_unidad', $this->idAlmacenFarmacia)
                            //->where('nro_lote', $this->nro_lote)
                            ->orderBy('fecha', 'desc')
                            ->first();*/

                        $saldoMedStock = MedicamentoStock::where('id_medicamento', $detalleIngresoAValidar->id_medicamento)
                            ->where('id_unidad', $this->idAlmacenFarmacia)
                            ->sum('stock_actualizado');

                        $saldo = 0;

                        if ($saldoMedStock) {
                            //DADO QUE ES UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                            $saldo = $saldoMedStock;
                        } else {
                            $saldo = $detalleIngresoAValidar->cantidad;
                        }

                        $nuevoMovimiento = Movimiento::create([
                            'id_corresponde' => $ingresoAValidar->id,
                            'id_identificador_tabla' => 1,
                            'dato_solicitante' => 'ALMACEN DE FARMACIA',
                            'nro_documento' => $ingresoAValidar->cod_ingreso,
                            'fecha' => $ingresoAValidar->fecha_ingreso,
                            'dato_interesado' => $ingresoAValidar->institucionExternaIngreso->razon_social,
                            'cantidad_ingreso' => $detalleIngresoAValidar->cantidad,
                            'cantidad_salida' => 0,
                            'saldo' => $saldo,
                            'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                            'id_estado_validacion' => 5,
                            'id_medicamento' => $detalleIngresoAValidar->id_medicamento,
                            'id_unidad' => $this->idAlmacenFarmacia,
                            'nro_lote' => $nroLotePorMedicamento,
                            'precio_unitario_peps' => $detalleIngresoAValidar->precio_unitario,
                            'fecha_vencimiento' => $detalleIngresoAValidar->fecha_vencimiento,
                            'id_user_created' => auth()->user()->id,
                            'stock_real' => true,
                        ]);

                        //FIN DE LA TABLA MOVIMIENTOS ----------------------------------------------------------------------
                    }
                }
                $ingresoAValidar->estado_validacion = 'VALIDADO';
                $ingresoAValidar->cod_asmi = $this->cod_asmi;
                $ingresoAValidar->id_user_valida = auth()->user()->id;
                $ingresoAValidar->fecha_valida = Carbon::now();
                $ingresoAValidar->save();
                DB::commit();
                $this->dispatch('ingresoValidadoCorrectamente');
                $this->dispatch('pg:eventRefresh-IngresosTable');
                $this->idIngreso = null;
            } else {
                DB::rollBack();
                $this->idIngreso = null;
                $this->dispatch('noExisteIngresoError');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->idIngreso = null;
            $this->dispatch('noExisteIngresoError');
        }
    }

    public function nuevoCodigoAsmi()
    {
        $ingresoAnterior = Ingreso::orderBy('id', 'desc')->where('cod_asmi', '!=', null)->first();
        if ($ingresoAnterior) {
            $ultimaGestion = Carbon::parse($ingresoAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->cod_asmi = $ingresoAnterior->cod_asmi + 1;
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

    public function mostrarIngresoMedicamento()
    {
        if ($this->idIngreso) {
            $ingreso = Ingreso::where('id', $this->idIngreso)->with([
                'procesoIngreso',
                'institucionExternaIngreso',
            ])->first();
            $this->nro_lote = $ingreso->nro_lote;
            $this->cod_asmi = $ingreso->cod_asmi;
            $this->cod_asmi_literal = $ingreso->cod_asmi . '/' . Carbon::parse($ingreso->created_at)->format('Y');
            $montoTotal = $ingreso->monto_total;
            $this->monto_total = number_format($montoTotal, 2, ',', '.');
            $this->nro_ingreso = $ingreso->cod_ingreso;
            $this->nro_ingreso_literal = $ingreso->procesoIngreso->sigla . '/' . $this->nro_ingreso . '/' . Carbon::parse($ingreso->created_at)->format('Y');
            $fecha = $ingreso->fecha_ingreso;
            $this->fecha_ingreso = Carbon::parse($fecha)->format('Y-m-d');
            // $this->fecha_ingreso_mostrar = Carbon::parse($fecha)->format('d/m/Y');
            $this->fecha_contrato = $ingreso->fecha_contrato;
            $this->fecha_recepcion = Carbon::parse($ingreso->fecha_recepcion)->format('Y-m-d');
            $this->hora_recepcion = Carbon::parse($ingreso->fecha_recepcion)->format('H:i:s');
            $this->proveedor = $ingreso->institucionExternaIngreso->razon_social;
            $this->tipo_proceso = $ingreso->procesoIngreso->nombre;

            $this->cod_cuce = $ingreso->cod_cuce ?: 'NO REGISTRADO';
            $this->responsables = $ingreso->responsables;

            //  $factura_select = Factura::where('id_ingreso', $this->idIngreso)->first();
            //  $this->nro_factura = $factura_select->nro_factura;
            //  $this->fecha_factura = Carbon::parse($factura_select->fecha)->format('Y-m-d');
            //  $totalFactura = $factura_select->monto_total;
            //  $this->total_factura = number_format($totalFactura, 2, ',', '.');
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

            $this->medicamentosIngreso = DetalleIngreso::where('id_ingreso', $ingreso->id)
                ->with('medicamentoDetalleIngreso')
                ->get();
            // dd($this->medicamentosIngreso);
        }
    }

    public function mostrarEnEditar($id_ingreso)
    {
        //dd('es');
        if ($id_ingreso) {
            $ingreso = Ingreso::where('id', $id_ingreso)->with([
                'procesoIngreso',
                'institucionExternaIngreso',
            ])->first();

            //dd($ingreso);
            $this->nro_ingreso = $ingreso->cod_ingreso;
            $this->nro_ingreso_literal_editar = $ingreso->procesoIngreso->sigla . '/' . $this->nro_ingreso . '/' . Carbon::parse($ingreso->created_at)->format('Y');
            $fecha = $ingreso->fecha_ingreso;
            // $this->fecha_ingreso = Carbon::parse($fecha)->format('Y-m-d');
            $this->fecha_ingreso_mostrar = Carbon::parse($fecha)->format('d/m/Y');
            $this->fecha_contrato = $ingreso->fecha_contrato;

            $this->tipo_proceso_editar = $ingreso->procesoIngreso->nombre;


            $this->medicamentosIngresoArray = DetalleIngreso::where('id_ingreso', $id_ingreso)
                ->with('medicamentoDetalleIngreso')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'codigo_medicamento' => $item->medicamentoDetalleIngreso->codigo_medicamento,
                        'nombre' => $item->medicamentoDetalleIngreso->nombre,
                        'forma_farmaceutica' => $item->medicamentoDetalleIngreso->forma_farmaceutica,
                        'concentracion' => $item->medicamentoDetalleIngreso->concentracion,
                        'cantidad' => $item->cantidad,
                        'precio_unitario' => $item->precio_unitario,
                        'precio_total' => $item->precio_total,
                        'pais_marca' => $item->pais_marca,
                        'fecha_vencimiento' => \Carbon\Carbon::parse($item->fecha_vencimiento)->format('Y-m-d'),
                    ];
                })
                ->toArray();
        }
    }

    #[On('editarFechaVencimientoFunction')]
    public function editarFechaVencimientoFunction()
    {
        // dd($this->medicamentosIngresoArray);
        foreach ($this->medicamentosIngresoArray as $med) {
            DetalleIngreso::where('id', $med['id'])
                ->update([
                    'fecha_vencimiento' => $med['fecha_vencimiento'],
                    'id_user_updated' => auth()->user()->id,
                ]);
        }
        $this->dispatch('editadoFecha');
        $this->dispatch('pg:eventRefresh-IngresosTable');
    }

    public function reseteeeeo()
    {
        $this->reset(
            'facturas',
            'medicamentosIngreso',
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
}
