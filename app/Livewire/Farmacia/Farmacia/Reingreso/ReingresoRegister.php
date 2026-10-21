<?php

namespace App\Livewire\Farmacia\Farmacia\Reingreso;

use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Reingreso;
use App\Models\Administracion\Persona;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Models\Farmacia\ReingresoMedicamento;
use App\Services\ListaUnidades;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReingresoRegister extends Component
{
    #[Validate]
    public $unidad, $nro_comprobante, $fecha_reingreso, $paciente, $nro_habitacion, $piso, $observacion, $codigo_reingreso;

    public $id_medicamento, $medicamentos;

    public $cantidad, $nombreMedicamento, $formaFarmaceutica, $fecha_vencimiento, $concentracion, $motivo_reingreso;
    public $medicamentosAgregados = [];
    public $unidades;
    public $id_persona, $nombreCompleto, $ci, $matricula, $personaSeleccionada = false, $noExiste = false;

    public $tipo_reingreso;

    public $origenReingreso = false;

    public function render()
    {
        return view('livewire.farmacia.farmacia.reingreso.reingreso-register');
    }

    public function mount()
    {
        $this->medicamentos = Medicamento::where('estado', true)
            ->orderBy('codigo_medicamento', 'asc')
            ->get();
        $this->fecha_reingreso = now()->toDateString();
        $this->unidades =  $this->listadoUnidades();
        $this->crearCodigos();
    }

    public function rules()
    {
        $reglas = [
            'unidad' => 'required',
            'fecha_reingreso' => 'required|date',
            'nro_comprobante' => 'required|min:1|max:10',
            'motivo_reingreso' => 'required',
        ];

        if ($this->origenReingreso) {
            $reglas = array_merge($reglas, [
                'nro_habitacion' => 'required|min:1|max:6',
                'piso' => 'required|min:1|max:6',
                'observacion' => 'required',
                'tipo_reingreso' => 'required',
            ]);
        }

        return $reglas;
    }

    public function ocultarFormularioPisos()
    {
        //dd($this->origenReingreso);
    }

    #[On('crearReingreso')]
    public function crearReingreso()
    {
        //dd($this->unidad);
        $this->validate();
        // dd('asdsa');
        DB::beginTransaction();
        try {

            if ($this->unidad == null) {
                $this->dispatch('agregarUnidad');
                return;
            }
            if ($this->personaSeleccionada == false && $this->origenReingreso) {
                $this->dispatch('ingresePersona');
                return;
            }

            if ($this->medicamentosAgregados === null || $this->medicamentosAgregados === []) {
                $this->dispatch('ingreseMedicamentosAReingresar');
                return;
            }

            if ($this->medicamentosAgregados != []) {
                $nuevoReingreso = Reingreso::create([
                    'codigo_reingreso' => $this->codigo_reingreso,
                    'fecha_ingreso' => $this->fecha_reingreso,
                    'observacion' => $this->observacion,
                    'motivo_reingreso' => $this->motivo_reingreso,
                    'piso' => $this->origenReingreso ? $this->piso : null,
                    'nro_habitacion' => $this->origenReingreso ? $this->nro_habitacion : null,
                    'id_paciente' => $this->origenReingreso ? $this->id_persona : null,
                    'id_unidad' => $this->unidad,
                    'estado' => true,
                    'tipo_reingreso' => $this->origenReingreso ? $this->tipo_reingreso : null,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($nuevoReingreso) {

                    foreach ($this->medicamentosAgregados as $key => $medicamentoAgregado) {

                        $precioUnitario = $this->registrarMedicamentoStock(76, $medicamentoAgregado, $nuevoReingreso);

                        $nuevoReingresoMedicamento = ReingresoMedicamento::create([
                            'id_reingreso' => $nuevoReingreso->id,
                            'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                            'fecha' => $this->fecha_reingreso,
                            'estado' => true,
                            'id_user_created' => auth()->user()->id,
                            'cantidad_ingreso' => $medicamentoAgregado['cantidad'],
                            'fecha_vencimiento' => $medicamentoAgregado['fecha_vencimiento'],
                            'precio_unitario' => $precioUnitario,
                        ]);
                    }

                    DB::commit();

                    //SE CREA EL PDF DE REINGRESO Y SE GUARDA EN EL FTP
                    $url = route('farmacia.farmacia.reingresos.pdf', ['modo' => 'create', 'id_reingreso' => $nuevoReingreso->id]);
                    $this->js("window.open('{$url}', '_blank');");

                    $this->dispatch('registroReingresoExitoso');
                    return redirect()->route('farmacia.reingresos.index');
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('registroReingresoNoExitoso');
        }
    }

    private function registrarMedicamentoStock($idUnidad, $medicamentoAgregado, $nuevoReingreso)
    {
        //dd($idUnidad, $medicamentoAgregado, $nuevoReingreso);
        $precioUnitarioReingreso = 0;
        $medicamentoStockAnterior = MedicamentoStock::where('id_medicamento', $medicamentoAgregado['id_medicamento'])
            ->where('id_unidad', $idUnidad)
            ->orderBy('nro_lote', 'desc')
            ->first();

        //  dd('BORIS ERES GAY Y NADIE LO NIEGA');
        //dd($this->noExiste);
        if ($this->noExiste == true) {
            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $medicamentoAgregado['cantidad'],
                'precio_unitario' => 0,
                'stock_actualizado' => $medicamentoAgregado['cantidad'],
                'nro_lote' =>  1,
                'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                'id_unidad' => $idUnidad,
                'ajuste_contable' => false,
                'id_user_created' => auth()->user()->id,
                'fecha_vencimiento' => $medicamentoAgregado['fecha_vencimiento'],
            ]);
        } else {
            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $medicamentoAgregado['cantidad'],
                'precio_unitario' => $medicamentoStockAnterior->precio_unitario,
                'stock_actualizado' => $medicamentoAgregado['cantidad'],
                'nro_lote' => $medicamentoStockAnterior->nro_lote + 1,
                'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                'id_unidad' => $idUnidad,
                'ajuste_contable' => false,
                'id_user_created' => auth()->user()->id,
                'fecha_vencimiento' => $medicamentoAgregado['fecha_vencimiento'],
            ]);
        }

        if ($nuevoMedicamentoStock) {

            $precioUnitarioReingreso = $nuevoMedicamentoStock->precio_unitario;

            /*$movimientoPrevio = Movimiento::where('id_medicamento', $medicamentoAgregado['id_medicamento'])
                ->where('id_unidad', $idUnidad)
                ->orderBy('id', 'desc')
                ->first();*/

            $saldoMedStock = MedicamentoStock::where('id_medicamento', $medicamentoAgregado['id_medicamento'])
                ->where('id_unidad', $idUnidad)
                ->sum('stock_actualizado');

            $saldo = 0;

            if ($saldoMedStock) {
                //DADO QUE ES COMO UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                $saldo = $saldoMedStock;
            } else {
                $saldo = $medicamentoAgregado['cantidad'];
            }

            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $nuevoReingreso->id,
                'id_identificador_tabla' => 5,
                'dato_solicitante' => 'FARMACIA',
                'nro_documento' => $nuevoReingreso->codigo_reingreso,
                'fecha' => $nuevoReingreso->fecha_ingreso,
                'dato_interesado' => $this->obtenerNombreUnidad($this->unidad),
                'cantidad_ingreso' => $medicamentoAgregado['cantidad'],
                'cantidad_salida' => 0,
                'saldo' => $saldo,
                'id_estado_validacion' => 7,
                'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                'id_unidad' => $idUnidad,
                'nro_lote' => $nuevoMedicamentoStock->nro_lote,
                'precio_unitario_peps' => $nuevoMedicamentoStock->precio_unitario,
                'id_user_created' => auth()->user()->id,
                'fecha_vencimiento' => $medicamentoAgregado['fecha_vencimiento'],
                'stock_real' => true,
            ]);
        }
        return $precioUnitarioReingreso;
    }

    public static function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    private static function obtenerNombreUnidad($idUnidad)
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidad)['nombre'];
    }

    public function listadoUnidades()
    {
        $servicio = new ListaUnidades();
        $listadoUnidades = $servicio->listaUnidades();
        // dd($listadoUnidades);
        if (!empty($listadoUnidades)) {
            return $unidadesSelect = $listadoUnidades;
        } else {
            return [];
        }
    }

    #[On('seleccionarPersona')]
    public function seleccionarPersona($data)
    {
        // dd($this->tipo_reingreso);
        // dd('asdsdf', $data);
        $persona = Persona::find($data);
        $this->id_persona = $persona->id;
        $this->nombreCompleto =
            ($persona->nombres ?? '') . ' ' .
            ($persona->p_apellido ?? '') . ' ' .
            ($persona->s_apellido ?? '');
        //dd($this->nombreCompleto);
        $this->ci = $persona->ci . ($persona->complemento ? '-' . $persona->complemento : '');
        $this->matricula = $persona->matricula_seguro;
        $this->dispatch('cerrarModalPersonas');
        $this->personaSeleccionada = true;
    }

    public function crearCodigos()
    {
        //dd('entra');
        $reingresoAnterior = Reingreso::where('farmacia', true)->orderBy('id', 'desc')->first();
        if ($reingresoAnterior) {
            $ultimoAño = Carbon::parse($reingresoAnterior->created_at)->format('Y');
            if ($ultimoAño == Carbon::now()->format('Y')) {
                $this->codigo_reingreso = $reingresoAnterior->codigo_reingreso + 1;
                $this->nro_comprobante = $this->codigo_reingreso . ' / ' . $ultimoAño;
            } else {
                $añoActual = Carbon::now()->format('Y');
                $this->codigo_reingreso = 1;
                $this->nro_comprobante = $this->codigo_reingreso . ' / ' . $añoActual;
            }
        } else {
            $añoActual = Carbon::now()->format('Y');
            $this->codigo_reingreso = 1;
            $this->nro_comprobante = $this->codigo_reingreso . ' / ' . $añoActual;
        }
    }


    public function agregarMedicamento()
    {
        //dd($this->obtenerIdFarmacia());
        // try {
        $this->validate(
            [
                'id_medicamento' => 'required',
                'formaFarmaceutica' => 'required',
                'concentracion' => 'required',
                'fecha_vencimiento' => 'required|date',
                'cantidad' => 'required|max:10',
            ]
        );

        $medicamento = Medicamento::with('detalleIngresoMedicamento')->where('id', $this->id_medicamento)->first();
        //dd($medicamento);
        $codigosExistentes = array_column($this->medicamentosAgregados, 'codigo_medicamento');
        // dd($codigosExistentes);

        $tieneRegistro =  MedicamentoStock::where('id_medicamento', $this->id_medicamento)
            ->where('id_unidad', 76)
            ->orderBy('nro_lote', 'desc')
            ->first();
        // dd($tieneRegistro);
        if (is_null($tieneRegistro)) {
            $this->noExiste = true;
            // $this->dispatch('sinRegistroPrevio');
            // return;
        }

        if (in_array($medicamento->codigo_medicamento, $codigosExistentes)) {
            $this->cantidad = null;
            $this->id_medicamento = null;
            $this->nombreMedicamento = null;
            $this->formaFarmaceutica = null;
            $this->dispatch('medicamentoDuplicado');
            $this->resetearDatos();
        } else {

            $this->medicamentosAgregados[] = [
                'id_medicamento' => $medicamento->id,
                'codigo_medicamento' => $medicamento->codigo_medicamento,
                'nombre' => $medicamento->nombre,
                'forma_farmaceutica' => $medicamento->forma_farmaceutica,
                'concentracion' => $medicamento->concentracion,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'motivo_reingreso' => $this->motivo_reingreso,
                'cantidad' => $this->cantidad,
            ];

            // dd($this->medicamentosAgregados);
            $this->actualizarListado();
            $this->dispatch('medicamentoAgregado');

            $this->dispatch('resetSelectMedicamento');
            $this->resetearDatos();
        }
        // } catch (\Throwable $th) {
        //     $this->dispatch('error');
        // }
    }
    #[On('muestraMedicamentoSolicitado')]
    public function muestraMedicamentoSolicitado()
    {
        if ($this->id_medicamento != null || $this->id_medicamento != '') {
            $medicamento = Medicamento::with('detalleIngresoMedicamento')->where('id', $this->id_medicamento)
                ->first();
            $this->nombreMedicamento = $medicamento->nombre;
            $this->formaFarmaceutica = $medicamento->forma_farmaceutica;
            $this->concentracion = $medicamento->concentracion ?? 'SIN DATO';
            // $this->fecha_vencimiento = $medicamento->detalleIngresoMedicamento->fecha_vencimiento;
        }
    }
    public function eliminarMedicamento($cod_med)
    {
        $this->medicamentosAgregados = array_filter($this->medicamentosAgregados, function ($medicamento) use ($cod_med) {
            return $medicamento['codigo_medicamento'] !== $cod_med;
        });
        $this->medicamentosAgregados = array_values($this->medicamentosAgregados);
        $this->actualizarListado();
    }

    public function actualizarListado()
    {
        $medicamentosAgregados = array_map(function ($codigos_medicamentos) {
            return $codigos_medicamentos['codigo_medicamento'];
        }, $this->medicamentosAgregados);
        $this->medicamentos = Medicamento::where('estado', true)->whereNotIn('codigo_medicamento', $medicamentosAgregados)->get();
    }

    public function resetearDatos()
    {
        // dd('asdsa');
        $this->reset('id_medicamento', 'nombreMedicamento', 'formaFarmaceutica', 'cantidad', 'fecha_vencimiento', 'concentracion');
    }

    public function atras()
    {
        return redirect()->route('farmacia.reingresos.index');
    }

    public function cancelar()
    {
        return redirect()->route('farmacia.reingresos.index');
    }
}
