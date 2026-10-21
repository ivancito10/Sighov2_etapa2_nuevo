<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\IngresoMedicamentos;

use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Farmacia\DetalleIngreso;
use App\Models\Farmacia\Factura;
use App\Models\Farmacia\Ingreso;
use App\Models\Farmacia\InstitucionExterna;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\TipoProceso;
use App\Services\ListaUnidades;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class IngresoMedicamentosRegister extends Component
{
    //ATRIBUTOS DE LA TABLA INGRESO
    public $institucionesExternas = [];

    public $procesos;

    #[Validate]
    public $cod_ingreso;

    #[Validate]
    public $fecha_ingreso;

    public $nro_lote;

    public $cod_asmi = 0;

    public $asmi = 0;

    public $monto_total = 0.00;

    public $monto_total_individual; //Para cuando se especifica el medicamento

    #[Validate]
    public $fecha_contrato;

    #[Validate]
    public $fecha_recepcion;

    #[Validate]
    public $hora_recepcion;

    #[Validate]
    public $id_institucion_externa;

    #[Validate]
    public $id_tipo_proceso;

    public $nombreTipoProceso;

    //ATRIBUTOS DE LA TABLA DETALLE INGRESO
    public $precio_unitario;

    public $cantidad;

    public $precio_total;

    #[Validate]
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

    public $nota_remision = false;

    //TABLA MEDICAMENTOS

    public $medicamentos;

    public $nombreMedicamento;

    public $formaFarmaceuticaMostrar;

    public $concentracionMostrar;

    public $medicamentosAgregados = [];

    public $facturaAgregadas = [];

    public $responsables;

    public $cod_cuce;

    public $pais_marca;

    public $monto_total_facturas = 0.00;

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.ingreso-medicamentos-register');
    }

    public function mount()
    {
        $this->fecha_ingreso = Carbon::now()->format('Y-m-d');
        $this->fecha_recepcion = Carbon::now()->format('Y-m-d');
        $this->hora_recepcion = Carbon::now()->format('H:i');
        $this->institucionesExternas = InstitucionExterna::where('estado', true)
            ->orderBy('razon_social', 'asc')->get();
        $this->procesos = TipoProceso::get();
        // dd($this->procesos);
        $this->medicamentos = Medicamento::where('estado', true)->get();
        // $this->nuevoCodigoAsmi();
        //$this->nuevoNumeroLote();
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

    public function cancelarIngreso()
    {
        return redirect()->route('almacen_farmacia.ingresos.index');
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();

        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    public $facturas = [];

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
                'nro_factura' => $this->nro_factura,
                'fecha_factura' => $this->fecha_factura,
                'monto_total_factura' => $this->monto_total_factura,
                'nota_remision' => $this->nota_remision,
            ];
            //dd($this->facturaAgregadas);
            $this->nro_factura = null;
            $this->fecha_factura = null;
            $this->monto_total_factura = null;
            $this->nota_remision = null;

            $this->dispatch('facturaAgregadaExitosamente');
        }
    }

    public function montosIguales($montoFacturas, $montoMedicamentos)
    {
        if ($montoFacturas === $montoMedicamentos) {
            return true;
        } else {
            return false;
        }
    }

    #[On('registrarIngresoMedicamento')]
    public function registrarIngresoMedicamento($texto)
    {
        // $this->responsables = 'SDFSDFSDF';
        //dd($this->id_institucion_externa);
        if ($this->id_institucion_externa === null) {
            $this->dispatch('sinProveedorAgregado');

            return;
        }

        $idAlmacenFarmacia = $this->obtenerIdAlmacen();
        //dd($idAlmacenFarmacia);

        if ($this->facturaAgregadas !== []) {

            if ($this->medicamentosAgregados !== []) {
                if ($this->montosIguales($this->monto_total_facturas, $this->monto_total)) {
                    $this->validate();
                    $fechaHoraRecepcion = $this->fecha_recepcion.' '.$this->hora_recepcion;
                    $datoInstitucionExterna = InstitucionExterna::find($this->id_institucion_externa);

                    DB::beginTransaction();
                    try {
                        $nuevoIngreso = Ingreso::create([
                            'fecha_ingreso' => Carbon::now(),
                            'fecha_contrato' => $this->fecha_contrato,
                            'fecha_recepcion' => Carbon::parse($fechaHoraRecepcion),
                            //'nro_lote' => $this->nro_lote,
                            // 'cod_asmi' => $this->cod_asmi,
                            'monto_total' => $this->monto_total,
                            'cod_cuce' => $this->cod_cuce,
                            'responsables' => $texto,
                            'id_institucion_externa' => $this->id_institucion_externa,
                            'id_tipo_proceso' => $this->id_tipo_proceso,
                            'cod_ingreso' => $this->cod_ingreso,
                            'estado_validacion' => 'REGISTRADO',
                            'estado' => true,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        //  dd($nuevoIngreso);
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

                            foreach ($this->medicamentosAgregados as $key => $medicamentoAgregado) {
                                $nuevoDetalleIngreso = DetalleIngreso::create([
                                    'precio_unitario' => $medicamentoAgregado['precio_unitario'],
                                    'cantidad' => $medicamentoAgregado['cantidad'],
                                    'precio_total' => $medicamentoAgregado['total'],
                                    'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                                    'id_ingreso' => $nuevoIngreso->id,
                                    'fecha_vencimiento' => $medicamentoAgregado['fecha_vencimiento'],
                                    'estado_adelanto' => false,
                                    //'nro_lote' => $this->nro_lote,
                                    'pais_marca' => $medicamentoAgregado['pais_marca'],
                                    'id_user_created' => auth()->user()->id,
                                ]);

                                if ($nuevoDetalleIngreso) {
                                    $contadorCorrectos = $contadorCorrectos + 1;
                                }
                            }

                            if ($contadorCorrectos == count($this->medicamentosAgregados)) {
                                $nuevoDetalleIngresoCreado = true;
                            } else {
                                $nuevoDetalleIngresoCreado = false;
                            }

                            if ($nuevaFacturaCreada && $nuevoDetalleIngresoCreado) {
                                // inicio de notificacion
                                $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
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
                                        'asunto' => 'ALMACEN DE MEDICAMENTOS - REGISTRO DE NUEVO INGRESO',
                                        'mensaje' => 'Se registró correctamente un nuevo ingreso con código: '.$procesoTipo->sigla.'/'.
                                            $nuevoIngreso->cod_ingreso.'/'.$gestionCreacion.', 
                                    siendo un proceso de tipo: '.$procesoTipo->nombre.'. Con código 
                                    ASMI Nro: '.$nuevoIngreso->cod_asmi.'/'.$gestionCreacion.'. Con un monto total de Bs. '.$nuevoIngreso->monto_total
                                            .'. Por el usuario: '.$nombreCreador,
                                    ]);
                                }
                                // fin de notificacion
                                DB::commit();
                                $this->dispatch('guardado');

                                //SE CREA PDF DE NUEVO INGRESO Y SE GUARDA EN FTP
                                $url = route('farmacia.almacenfarmacia.ingresos.pdf', ['modo' => 'create', 'rowId' => $nuevoIngreso->id]);
                                $this->js("window.open('{$url}', '_blank');");
                                //$this->js('window.open("/farmacia/almacenfarmacia/ingresos/' . $this->id_ingreso . '", "_blank")');

                                return redirect()->route('almacen_farmacia.ingresos.index');
                            } else {
                                DB::rollBack();
                                $this->dispatch('noGuardado');
                            }
                        } else {
                            DB::rollBack();
                            $this->dispatch('noGuardado');
                        }
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
        /* if ($this->precio_unitario != null && $this->cantidad != null) {
            $this->monto_total_individual = round($this->precio_unitario * $this->cantidad, 2);
        } else {
            $this->monto_total_individual = 0.00;
        } BORIS SI LEES ESTO, ES PORQUE ERES GAY , PERO BIEN GUEY JIJI */

        // dd($this->monto_total_individual, $this->cantidad);
        if ($this->cantidad > 0) {
            $this->precio_unitario = round($this->monto_total_individual / $this->cantidad, 2);
        } else {
            $this->precio_unitario = 0;
        }
    }

    public function agregarMedicamento()
    {
        $this->validate(
            [
                'id_medicamento' => 'required',
                'nombreMedicamento' => 'required',
                'formaFarmaceuticaMostrar' => 'required',
                'fecha_vencimiento' => 'required|date|after:today',
                'monto_total_individual' => 'required| gt:0|max:9',
                'cantidad' => 'required| max:8 | gt:0',
                // 'precio_unitario' => 'gt:0',
                'pais_marca' => 'required',
            ],
            [
                'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha actual.',
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
            $this->concentracionMostrar = null;

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
            $this->concentracionMostrar = null;
            $this->pais_marca = null;

            $this->dispatch('refreshSelect2');
            $this->dispatch('medicamentoAgregadoCorrectamente');
        }
    }

    public function actualizarListaMedicamentos()
    {
        $codigosAgregados = array_map(function ($codsMeds) {
            return $codsMeds['codigo_medicamento'];
        }, $this->medicamentosAgregados);

        $this->medicamentos = Medicamento::where('estado', true)->whereNotIn('codigo_medicamento', $codigosAgregados)->get();
    }

    public function tipoProcesoSeleccionado()
    {
        // dd('dfsd', $this->id_tipo_proceso);
        if ($this->id_tipo_proceso != null || $this->id_tipo_proceso != '') {
            // dd('dfsd');
            $procesoSelected = TipoProceso::where('id', $this->id_tipo_proceso)->first();
            $this->nombreTipoProceso = $procesoSelected->nombre;
        } else {
            $this->nombreTipoProceso = null;
        }
    }

    public function eliminarMedicamento($cod_med, $monto_individual)
    {
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
    }

    public function nuevoCodigoAsmi()
    {
        $ingresoAnterior = Ingreso::orderBy('id', 'desc')->first();
        if ($ingresoAnterior) {
            $ultimaGestion = Carbon::parse($ingresoAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->cod_asmi = $ingresoAnterior->cod_asmi + 1;
                $this->asmi = $this->cod_asmi.'/'.$ultimaGestion;
            } else {
                $ultimaGestion = Carbon::now()->format('Y');
                $this->cod_asmi = 1;
                $this->asmi = $this->cod_asmi.'/'.$ultimaGestion;
            }
        } else {
            $ultimaGestion = Carbon::now()->format('Y');
            $this->cod_asmi = 1;
            $this->asmi = $this->cod_asmi.'/'.$ultimaGestion;
        }
    }

    public function eliminarFactura($codigoFactura)
    {
        foreach ($this->facturaAgregadas as $key => $factura) {
            //dd($factura);
            if ($factura['nro_factura'] === $codigoFactura) {
                $this->monto_total_facturas -= $factura['monto_total_factura'];
                unset($this->facturaAgregadas[$key]);
                break;
            }
        }
        $this->facturaAgregadas = array_values($this->facturaAgregadas);
    }

    public function nuevoNumeroLote()
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
