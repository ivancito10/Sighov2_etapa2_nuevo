<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\IngresoMedicamentos;

use App\Models\Farmacia\DetalleIngreso;
use App\Models\Farmacia\Factura;
use App\Models\Farmacia\Ingreso;
use App\Models\Farmacia\InstitucionExterna;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Movimiento;
use App\Models\Farmacia\TipoProceso;
use App\Services\ListaUnidades;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class IngresoMedicamentosEdit extends Component
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

    public $id_medicamento;

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

    //TABLA MEDICAMENTOS
    public $medicamentos;

    public $nombreMedicamento;

    public $formaFarmaceuticaMostrar;

    public $concentracionMostrar;

    public $responsables;

    public $cod_cuce;

    public $medicamentosAgregadosOriginales;

    public $medicamentosAgregados = [];

    public $facturaAgregadas = [];

    public $monto_total_facturas = 0.00;

    public $pais_marca;

    public $nota_remision = false;

    public $monto_total_individual;

    public $facturasOriginales;

    public $idUserCreatedIngreso;

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.ingreso-medicamentos-edit');
    }

    public function mount()
    {
        $this->institucionesExternas = InstitucionExterna::all();
        $this->procesos = TipoProceso::get();
        $this->medicamentos = Medicamento::where('estado', true)->get();
        $this->mostrarIngreso($this->idIngreso);
        $this->dispatch('cargarIdInstitucionExterna', id: $this->id_institucion_externa);
    }

    public function rules()
    {
        return [
            'cod_ingreso' => 'required',
            'fecha_ingreso' => 'required|date',
            'id_institucion_externa' => 'required',
            'id_tipo_proceso' => 'required',
            'fecha_contrato' => 'required|date',
            'fecha_recepcion' => 'required|date',
            'hora_recepcion' => 'required',
        ];
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
        $this->hora_recepcion = Carbon::parse($ingreso->fecha_recepcion)->format('H:i:s');
        $this->cod_ingreso = $ingreso->cod_ingreso;
        $this->cod_asmi = $ingreso->cod_asmi;
        $this->asmi = $ingreso->cod_asmi . '/' . Carbon::parse($ingreso->created_at)->format('Y');
        $this->nro_lote = $ingreso->nro_lote;
        $this->monto_total = $ingreso->monto_total;
        $this->responsables = $ingreso->responsables;
        $this->cod_cuce = $ingreso->cod_cuce;
        $this->idUserCreatedIngreso = $ingreso->id_user_created;

        $listaMedicamentos = DetalleIngreso::where('id_ingreso', $idIngreso)->with('medicamentoDetalleIngreso')->get();
        foreach ($listaMedicamentos as $key => $datosMedicamento) {
            $this->medicamentosAgregados[] = [
                'id_medicamento' => $datosMedicamento->medicamentoDetalleIngreso->id,
                'codigo_medicamento' => $datosMedicamento->medicamentoDetalleIngreso->codigo_medicamento,
                'nombre' => $datosMedicamento->medicamentoDetalleIngreso->nombre,
                'forma_farmaceutica' => $datosMedicamento->medicamentoDetalleIngreso->forma_farmaceutica,
                'concentracion' => $datosMedicamento->medicamentoDetalleIngreso->concentracion,
                'fecha_vencimiento' => $datosMedicamento->fecha_vencimiento,
                'precio_unitario' => $datosMedicamento->precio_unitario,
                'cantidad' => $datosMedicamento->cantidad,
                'pais_marca' => $datosMedicamento->pais_marca,
                'total' => $datosMedicamento->precio_total,
            ];
        }

        $this->medicamentosAgregadosOriginales = $this->medicamentosAgregados;

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

    #[On('medicamentoSeleccionado')]
    public function medicamentoSeleccionado()
    {
        if ($this->id_medicamento != null || $this->id_medicamento != '') {
            $nombreMed = Medicamento::where('id', $this->id_medicamento)->first();
            $this->nombreMedicamento = $nombreMed->nombre;
            $this->formaFarmaceuticaMostrar = $nombreMed->forma_farmaceutica;
            $this->concentracionMostrar = $nombreMed->concentracion;
        } else {
            $this->nombreMedicamento = null;
            $this->concentracionMostrar = null;
            $this->formaFarmaceuticaMostrar = null;
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

    public function montosIguales($montoFacturas, $montoMedicamentos)
    {
        //dd($montoFacturas, $montoMedicamentos);
        if ((int) $montoFacturas === (int) $montoMedicamentos) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarMedicamento($cod_med, $monto_individual)
    {
        //dd('entra locoooo');
        $this->monto_total = round($this->monto_total - $monto_individual, 2);
        $this->monto_total_factura = $this->monto_total;
        $this->medicamentosAgregados = array_filter($this->medicamentosAgregados, function ($medicamento) use ($cod_med) {
            return $medicamento['codigo_medicamento'] !== $cod_med;
        });

        $this->medicamentosAgregados = array_values($this->medicamentosAgregados);

        if ($this->medicamentosAgregados == []) {
            $this->monto_total = 0;
            $this->monto_total_factura = $this->monto_total;
        }
        $this->actualizarListaMedicamentos();
        $this->dispatch('medicamentoEliminadoCorrectamente');
    }

    public function actualizarListaMedicamentos()
    {
        $codigosAgregados = array_map(function ($codsMeds) {
            return $codsMeds['codigo_medicamento'];
        }, $this->medicamentosAgregados);

        $this->medicamentos = Medicamento::where('estado', true)->whereNotIn('codigo_medicamento', $codigosAgregados)->get();
    }

    public function calcularTotal()
    {
        $this->monto_total = 0;
        $this->monto_total_factura = 0;

        /*  foreach ($this->medicamentosAgregados as $index => $medicamento) {
            $precioUnitario = floatval($medicamento['precio_unitario'] ?? 0);
            $cantidad = intval($medicamento['cantidad'] ?? 0);

            if ($precioUnitario * $cantidad == 0) {
                $this->dispatch('valoresVacios');
            }

            $total = $precioUnitario * $cantidad;
            $this->medicamentosAgregados[$index]['total'] = $total;
            $this->monto_total += $total;
            $this->monto_total_factura += $total;
        } */

        foreach ($this->medicamentosAgregados as $index => $medicamento) {
            $total = floatval($medicamento['total'] ?? 0);
            $cantidad = floatval($medicamento['cantidad'] ?? 0);

            if ($total == 0 || $cantidad == 0) {
                $this->medicamentosAgregados[$index]['precio_unitario'] = 0;
                //$this->dispatch('valoresVacios');
            }

            $precioUnitario = $total / $cantidad;
            $this->medicamentosAgregados[$index]['precio_unitario'] = round($precioUnitario, 2);
            $this->monto_total += $total;
            $this->monto_total_factura += $total;
        }
    }

    public function obtenerNombreAlmacen()
    {
        $datosUnidades = new ListaUnidades();

        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['nombre'];
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();

        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    #[On('editarIngresoMedicamento')]
    public function editarIngresoMedicamento($texto)
    {
        if ($this->id_institucion_externa === null) {
            $this->dispatch('sinProveedorAgregado');

            return;
        }

        if ($this->facturaAgregadas !== []) {
            if ($this->medicamentosAgregados != []) {
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
                        $ingresoEditado->cod_ingreso = $this->cod_ingreso;
                        $ingresoEditado->cod_cuce = $this->cod_cuce;
                        $ingresoEditado->responsables = $texto;
                        $ingresoEditado->fecha_recepcion = Carbon::parse($fechaHoraRecepcion);
                        $ingresoEditado->fecha_contrato = $this->fecha_contrato;
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
                                'id_user_updated' => auth()->user()->id,
                            ]);
                        }

                        //FIN DE LA EDICION DE FACTURAS

                        //EDICION DE MEDICAMENTOS CHEEEE

                        foreach ($this->medicamentosAgregadosOriginales as $key => $medicamentoAgregadoOriginal) {

                            $detalleIngreso = DetalleIngreso::where('id_ingreso', $this->idIngreso)
                                ->where('id_medicamento', $medicamentoAgregadoOriginal['id_medicamento'])
                                ->first();

                            //TRATAMIENTO DE LA TABLA DETALLE MOVIMIENTO: MATEN LA TABLA, salvaguarden EL ID USER CREATED!!! ESTO YA NO FUNCIONA PANITAAA ATTE. YO MISMO

                            $detalleIngreso->delete();
                        }

                        foreach ($this->medicamentosAgregados as $medicamentoAgregado) {
                            $nuevoDetalleIngreso = DetalleIngreso::create([
                                'precio_unitario' => $medicamentoAgregado['precio_unitario'],
                                'cantidad' => $medicamentoAgregado['cantidad'],
                                'precio_total' => $medicamentoAgregado['total'],
                                'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                                'id_ingreso' => $this->idIngreso,
                                'nro_lote' => $this->nro_lote,
                                'id_user_created' => $this->idUserCreatedIngreso,
                                'id_user_updated' => auth()->user()->id,
                                'pais_marca' => $medicamentoAgregado['pais_marca'],
                                'fecha_vencimiento' => $medicamentoAgregado['fecha_vencimiento'],
                            ]);
                        }

                        //FIN DE LA EDICION DE MEDICAMENTOS

                        DB::commit();
                        $this->dispatch('guardado');

                        //PDF EDITADO DE INGRESO Y SE GUARDA EN FTP
                        $url = route('farmacia.almacenfarmacia.ingresos.pdf', ['modo' => 'edit', 'rowId' => $ingresoEditado->id]);
                        $this->js("window.open('{$url}', '_blank');");

                        return redirect()->route('almacen_farmacia.ingresos.index');
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        $this->dispatch('noGuardado');
                    }
                } else {
                    $this->dispatch('montosDesiguales');
                }
            } else {
                $this->dispatch('medicamentosVacio');
            }
        } else {
            $this->dispatch('facturasVacias');
        }
    }

    public function agregarMedicamento()
    {
        $this->validate(
            [
                'id_medicamento' => 'required',
                'nombreMedicamento' => 'required',
                'formaFarmaceuticaMostrar' => 'required',
                'fecha_vencimiento' => 'required| date',
                'monto_total_individual' => 'required| gt:0|max:9',
                'cantidad' => 'required| max:8 | gt:0',
                // 'precio_unitario' => 'gt:0',
                'pais_marca' => 'required',
            ]
        );

        $this->calcularMontoTotalIndividual();

        $datosMedicamento = Medicamento::where('id', $this->id_medicamento)->first();

        $codigosExistentes = array_column($this->medicamentosAgregados, 'codigo_medicamento');
        if (in_array($datosMedicamento->codigo_medicamento, $codigosExistentes)) {
            $this->precio_unitario = null;
            $this->cantidad = null;
            $this->monto_total_individual = 0.00;
            //$monto_individual_total = 0.00;
            $this->fecha_vencimiento = null;
            $this->id_medicamento = null;
            $this->nombreMedicamento = null;
            $this->formaFarmaceuticaMostrar = null;

            $this->dispatch('refreshSelect2');
            $this->dispatch('medicamentoDuplicado');
        } else {
            // dd($this->precio_unitario , $this->cantidad, $this->monto_total_individual);
            //$monto_individual_total = $this->precio_unitario * $this->cantidad;
            //dd($monto_individual_total);
            $this->medicamentosAgregados[] = [
                'id_medicamento' => $datosMedicamento->id,
                'codigo_medicamento' => $datosMedicamento->codigo_medicamento,
                'nombre' => $datosMedicamento->nombre,
                'forma_farmaceutica' => $datosMedicamento->forma_farmaceutica,
                'concentracion' => $datosMedicamento->concentracion,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'pais_marca' => strtoupper($this->pais_marca),
                'precio_unitario' => $this->precio_unitario,
                'cantidad' => $this->cantidad,
                'total' => $this->monto_total_individual,
            ];

            $this->monto_total = round($this->monto_total + $this->monto_total_individual, 2);
            $this->monto_total_factura = $this->monto_total;

            $this->actualizarListaMedicamentos();

            $this->precio_unitario = null;
            $this->cantidad = null;
            $this->monto_total_individual = 0.00;
            //$monto_individual_total = 0.00;
            $this->fecha_vencimiento = null;
            $this->id_medicamento = null;
            $this->nombreMedicamento = null;
            $this->formaFarmaceuticaMostrar = null;
            $this->pais_marca = null;

            $this->dispatch('refreshSelect2');
            $this->dispatch('medicamentoAgregadoCorrectamente');
        }
    }

    public function cancelar()
    {
        return redirect()->route('almacen_farmacia.ingresos.index');
    }

    public function atras()
    {
        return redirect()->route('almacen_farmacia.ingresos.index');
    }
}
