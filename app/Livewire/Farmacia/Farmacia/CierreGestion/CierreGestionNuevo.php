<?php

namespace App\Livewire\Farmacia\Farmacia\CierreGestion;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\CierreGestion;
use App\Models\Administracion\Persona;
use App\Models\Farmacia\ControlCierreGestion;
use App\Models\Farmacia\Movimiento;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use Livewire\Attributes\Validate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Farmacia\MedicamentoStock;

class CierreGestionNuevo extends Component
{
    public $id;
    public $cod_medicamento, $medicamento, $forma_farmaceutica, $concentracion,
        $unidadSolicitante, $cargoSolicitante, $nombre_completo, $carnet,
        $stock_actual, $id_cierre, $ingreso, $salida, $saldo,
        $precio_anterior, $hoy;
    #[Validate]
    public $cantidad_fisica, $cantidad_bajas, $diferencia, $diferencia_mostrar, $fecha_vencimiento, $fecha_vencimiento_baja;
    public $idCierreGestionRegistrado;
    public $idUnidadFarmacia;

    public $fechaDefectoMomentaneo;
    public $opcionesBajaMedicamento;

    public function render()
    {
        return view('livewire.farmacia.farmacia.cierre-gestion.cierre-gestion-nuevo');
    }

    public function mount()
    {
        $this->hoy = now()->toDateString();
        $this->idUnidadFarmacia = 76;
        // $this->mostrarMedicamento($this->id);

        $this->opcionesBajaMedicamento = false;
        $this->fechaDefectoMomentaneo = Carbon::parse('2027-12-31')->format('Y-m-d');
    }

    // ---sion footer
    #[On('formulario_cierre_gestion_farmacia')]
    public function formulario_cierre_gestion_farmacia($data)
    {
        //dd($data);
        $this->id = $data;
        //  dd($this->idMed);
        $this->mostrarMedicamento($data);
        $this->dispatch('cerrar_listado_modal_farmacia');
        $this->dispatch('registrar_cierre_farmacia');
        $this->reseteoDAtos();

        $this->fecha_vencimiento = $this->fechaDefectoMomentaneo;
        //$this->skipRender();
    }

    public function rules()
    {
        $rules = [
            'cantidad_fisica' => 'required|gte:0',
            'diferencia_mostrar' => 'required',
            'fecha_vencimiento' => 'required|date',
            'cantidad_bajas' => 'nullable|integer|min:0',
        ];

        if ($this->cantidad_bajas > 0) {
            $rules['fecha_vencimiento_baja'] = 'required|date';
        } else {
            $rules['fecha_vencimiento_baja'] = 'nullable|date';
        }

        return $rules;
    }

    public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    public function atras()
    {
        // return redirect()->route('farmacia.cierre-gestion.index');
    }

    public function camcelarCierreFarmacia()
    {
        $this->dispatch('cierre_cancelar_farmacia');
        // return redirect()->route('farmacia.cierre-gestion.index');
    }

    public function mostrarMedicamento($id)
    {
        //dd('asds');
        if ($id) {
            $medicamento = Medicamento::find($id);
            // dd($medicamento);
            $this->cod_medicamento = $medicamento->codigo_medicamento;
            $this->medicamento = $medicamento->nombre;
            $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
            $this->concentracion = $medicamento->concentracion;

            /*$stock = Movimiento::where('id_medicamento', $id)
                ->where('id_unidad', $this->idUnidadFarmacia)
                ->orderBy('id', 'desc')
                ->first();*/

            $stockAlmacen = MedicamentoStock::where('id_medicamento', $id)
                ->where('id_unidad', $this->idUnidadFarmacia)
                ->sum('stock_actualizado');

            $this->stock_actual = $stockAlmacen ?? 0;
            $id_persona = auth()->user()->id_persona;
            $personaData = $this->obtenerDatosPersona($id_persona);
            $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
            $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

            $persona = Persona::find($id_persona);
            $this->nombre_completo = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
            if ($persona->complemento != null) {
                $this->carnet = $persona->ci . '-' . $persona->complemento;
            } else {
                $this->carnet = $persona->ci;
            }
        }
    }

    #[On('funcion_guardar_cierre_gestion_farmacia')]
    public function funcion_guardar_cierre_gestion_farmacia()
    {
        if (trim($this->fecha_vencimiento) === '') {
            $this->fecha_vencimiento = null;
        }

        if (trim($this->fecha_vencimiento_baja) === '') {
            $this->fecha_vencimiento_baja = null;
        }

        if (trim($this->cantidad_bajas) === "") {
            $this->cantidad_bajas = null;
        }

        $this->validate();
        DB::beginTransaction();
        //try {

        $cierre_ant = CierreGestion::where('id_medicamento', $this->id)
            ->where('id_unidad', $this->idUnidadFarmacia)
            ->orderBy('fecha_registro', 'desc')
            ->first();
        // dd($cierre_ant);
        if ($cierre_ant) {
            $fecha_validate = Carbon::parse($cierre_ant->fecha_registro);
            $diasDiferencia = $fecha_validate->diffInDays(Carbon::now());

            if ($diasDiferencia > 3) {
                $cierre = CierreGestion::create([
                    'cantidad_fisica' => $this->cantidad_fisica,
                    'cantidad_sistema' => $this->stock_actual,
                    'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                    'diferencia' => $this->diferencia,
                    'id_unidad' => $this->idUnidadFarmacia,
                    'id_medicamento' => $this->id,
                    'fecha_vencimiento' => $this->fecha_vencimiento,
                    'fecha_vencimiento_baja' => $this->fecha_vencimiento_baja,
                    'cantidad_bajas' => $this->cantidad_bajas,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($cierre) {

                    $this->idCierreGestionRegistrado = $cierre->id;
                    if ($this->diferencia > 0) {
                        //dd('la diferencia salio mayor que 0'); //SIGNIFICA QUE HAY MÁS EN SISTEMA QUE EN FÍSICO ---> Procede: SALIDA
                        $this->obtencionDatosMovimientos($cierre->id, $this->id, abs($this->diferencia), $this->fecha_vencimiento, 1);
                    } elseif ($this->diferencia === 0) {
                        //dd('la diferencia salio 0'); //SIGNIFICA QUE NO EXISTE DIFERENCIA, ENTONCES NO SE HACER NADA MÁS QUE ACTUALIZAR EL ESTADO DE CIERRE_GESTION A TRUE ---> Solo se actualiza el último registro
                        $this->obtencionDatosMovimientos($cierre->id, $this->id, abs($this->diferencia), $this->fecha_vencimiento, 2);
                    } else {
                        //dd('la diferencia salio menor que 0'); //SIGNIFICA QUE HAY MÁS FISICO QUE EN SISTEMA ---> Se realiza un: INGRESO
                        $this->obtencionDatosMovimientos($cierre->id, $this->id, abs($this->diferencia), $this->fecha_vencimiento, 3);
                    }

                    $this->dispatch('cierre_gestion_registrado_farmacia');
                    $this->dispatch('pg:eventRefresh-CierreFarmaciaTable');
                    $this->dispatch('pg:eventRefresh-MedicamentosFarmaciaTable');
                    $this->reseteoDAtos();

                    //return redirect()->route('farmacia.cierre-gestion.index');
                }
            } else {
                $this->dispatch('hay_registro');
            }
        } else {

            $cierre = CierreGestion::create([
                'cantidad_fisica' => $this->cantidad_fisica,
                'cantidad_sistema' => $this->stock_actual,
                'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                'diferencia' => $this->diferencia,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento' => $this->id,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'fecha_vencimiento_baja' => $this->fecha_vencimiento_baja,
                'cantidad_bajas' => $this->cantidad_bajas,
                'id_user_created' => auth()->user()->id,
            ]);

            if ($cierre) {

                $this->idCierreGestionRegistrado = $cierre->id;
                if ($this->diferencia > 0) {
                    //dd('la diferencia salio mayor que 0'); //SIGNIFICA QUE HAY MÁS EN SISTEMA QUE EN FÍSICO ---> Procede: SALIDA
                    $this->obtencionDatosMovimientos($cierre->id, $this->id, abs($this->diferencia), $this->fecha_vencimiento, 1);
                } elseif ($this->diferencia === 0) {
                    //dd('la diferencia salio 0'); //SIGNIFICA QUE NO EXISTE DIFERENCIA, ENTONCES NO SE HACER NADA MÁS QUE ACTUALIZAR EL ESTADO DE CIERRE_GESTION A TRUE ---> Solo se actualiza el último registro
                    $this->obtencionDatosMovimientos($cierre->id, $this->id, abs($this->diferencia), $this->fecha_vencimiento, 2);
                } else {
                    //dd('la diferencia salio menor que 0'); //SIGNIFICA QUE HAY MÁS FISICO QUE EN SISTEMA ---> Se realiza un: INGRESO
                    $this->obtencionDatosMovimientos($cierre->id, $this->id, abs($this->diferencia), $this->fecha_vencimiento, 3);
                }

                $this->dispatch('cierre_gestion_registrado_farmacia');
                $this->dispatch('pg:eventRefresh-CierreFarmaciaTable');
                $this->dispatch('pg:eventRefresh-MedicamentosFarmaciaTable');

                $this->reseteoDAtos();
                //  return redirect()->route('farmacia.cierre-gestion.index');
            }
        }
        DB::commit();
        /*} catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('cierre_error_farmacia');
        }*/
    }

    public function reseteoDAtos()
    {
        $this->reset('cantidad_fisica', 'cantidad_bajas', 'diferencia', 'diferencia_mostrar', 'fecha_vencimiento');
    }

    public function procesarCantidad()
    {
        if ($this->cantidad_fisica != null) {
            $this->diferencia =  $this->stock_actual - $this->cantidad_fisica;
            $this->diferencia_mostrar = abs($this->diferencia);
            if ($this->diferencia < 0) {
                $this->ingreso = abs($this->diferencia);
                $this->saldo = $this->ingreso + $this->stock_actual;
                //dd("s<f", $this->stock_actual, $this->cantidad_fisica, $this->diferencia, $this->ingreso, $this->saldo);

            } elseif ($this->diferencia > 0) {
                $this->salida = $this->diferencia;
                $this->saldo = $this->stock_actual - $this->salida;
                //dd("s>f",  $this->stock_actual, $this->cantidad_fisica, $this->diferencia, $this->salida);
            }
        } else {
            $this->diferencia = 0;
        }
        // $this->skipRender();
    }

    public function obtencionDatosMovimientos($idCierreGestion, $idMedicamento, $cantidad, $fechaVencimiento, $tipoDiferencia)
    {
        //dd($idCierreGestion, $idMedicamento, $cantidad, $fechaVencimiento, $tipoDiferencia, $this->diferencia);
        /*
            DESCRIPCION DE TIPO DE DIFERENCIA
                SI ES "1" SIGNIFICA QUE ES SALIDA (HAY MAS EN SISTEMA)
                SI ES "2" SIGNIFICA QUE NO HAY DIFERENCIA
                SI ES "3" SIGNIFICA QUE ES INGRESO (HAY MENOS EN EL SISTEMA)
        */

        if ($tipoDiferencia === 1) {
            $datosNecesariosParaMovimientosSalida = [
                'id_corresponde' => $idCierreGestion,
                'id_unidad' => $this->idUnidadFarmacia,
                'nro_documento' => 'CIERRE-GESTION-FARMACIA' . Carbon::now()->format('Y'),
            ];

            $this->procesarCierreGestionSalida($idMedicamento, $cantidad, $datosNecesariosParaMovimientosSalida, $fechaVencimiento);
        } elseif ($tipoDiferencia === 2) {

            $this->procesarCierreGestionSinDiferencia($idMedicamento, $fechaVencimiento, $cantidad, $idCierreGestion);
        } elseif ($tipoDiferencia === 3) {

            $this->procesarCierreGestionIngreso($idMedicamento, $cantidad, $fechaVencimiento, $idCierreGestion);
        }
    }

    public function procesarCierreGestionSalida(int $idMedicamento, int $cantidad, array $datosNecesarios, $fechaVencimiento)
    {
        $lotes = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $datosNecesarios['id_unidad'])
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
                'id_corresponde' => $datosNecesarios['id_corresponde'],
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $datosNecesarios['id_unidad'],
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => 'CIERRE DE GESTIÓN - ' . Carbon::now()->format('Y'),
                'nro_documento' => $datosNecesarios['nro_documento'],
                'dato_interesado' => 'FARMACIA',
                'nro_lote' => $lote->nro_lote,
                'precio_unitario_peps' => $lote->precio_unitario,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
            ];

            $lote->stock_actualizado = $disponible - $cantidadARestar;
            $lote->cierre_gestion = true;
            $lote->fecha_vencimiento = $fechaVencimiento;
            $lote->save();

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => $lote->id,
                'id_movimiento' => null,
                'cantidad' => $cantidadARestar,
                'tipo_movimiento' => 'SALIDA',
            ]);

            $cantidad -= $cantidadARestar;

            $this->registrarMovimientoSalida($datosParaMovimientos);
        }
    }

    public function registrarMovimientoSalida($datosNecesarios)
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
            'id_identificador_tabla' => 4,
            'dato_solicitante' => $datosNecesarios['dato_solicitante'],
            'nro_documento' => $datosNecesarios['nro_documento'],
            'fecha' => Carbon::now(),
            'dato_interesado' => $datosNecesarios['dato_interesado'],
            'cantidad_ingreso' => 0,
            'cantidad_salida' => $datosNecesarios['cantidad'],
            'saldo' => $nuevoSaldo,
            'id_estado_validacion' => 2,
            'id_medicamento' => $datosNecesarios['id_medicamento'],
            'id_unidad' => $datosNecesarios['id_unidad'],
            'nro_lote' => $datosNecesarios['nro_lote'],
            'id_user_created' => auth()->user()->id,
            'precio_unitario_peps' => $datosNecesarios['precio_unitario_peps'],
            'fecha_vencimiento' => $datosNecesarios['fecha_vencimiento'],
            'cierre_gestion' => true,
            'stock_real' => true,
        ]);

        $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
            'id_cierre_gestion' => $this->idCierreGestionRegistrado,
            'id_unidad' => $this->idUnidadFarmacia,
            'id_medicamento_stock' => null,
            'id_movimiento' => $nuevoMovimiento->id,
            'cantidad' => $datosNecesarios['cantidad'],
            'tipo_movimiento' => 'SALIDA',
        ]);

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }

    public function procesarCierreGestionIngreso($idMedicamento, $cantidad, $fechaVencimiento, $idCierreGestion)
    {
        $maxNroLote = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $this->idUnidadFarmacia)
            ->max('nro_lote');

        if ($maxNroLote) {
            $ultimoPrecioUnitario = MedicamentoStock::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $this->idUnidadFarmacia)
                ->where('nro_lote', $maxNroLote)
                ->first();

            $nroLotePorMedicamento = $maxNroLote ? $maxNroLote + 1 : 1;

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => $ultimoPrecioUnitario->precio_unitario,
                'stock_actualizado' => $cantidad,
                'nro_lote' => $nroLotePorMedicamento,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idUnidadFarmacia,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => $nuevoMedicamentoStock->id,
                'id_movimiento' => null,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);

            // FIN DE LA INSERCION EN LA TABLA MEDICAMENTOS STOCK -----------------------------------------------------

            //PARA LA TABLA MOVIMIENTO

            /*$movimientoPrevio = Movimiento::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $this->idUnidadFarmacia)
                //->where('nro_lote', $this->nro_lote)
                ->orderBy('id', 'desc')
                ->first();*/

            $saldoMedStock = MedicamentoStock::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $this->idUnidadFarmacia)
                ->sum('stock_actualizado');

            $saldo = 0;

            if ($saldoMedStock) {
                //DADO QUE ES UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                $saldo = $saldoMedStock;
            } else {
                $saldo = $cantidad;
            }

            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $idCierreGestion,
                'id_identificador_tabla' => 4,
                'dato_solicitante' => 'FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => Carbon::now(),
                'dato_interesado' => 'CIERRE DE GESTION - ' . Carbon::now()->format('Y'),
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $saldo,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idUnidadFarmacia,
                'nro_lote' => $nroLotePorMedicamento,
                'precio_unitario_peps' => $ultimoPrecioUnitario->precio_unitario,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => null,
                'id_movimiento' => $nuevoMovimiento->id,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);
        } else {

            //PARA MEDICAMENTOS STOCK EN CIERRE DE GESTION (LOGICA FUNCIONAL UNICAMENTE PARA LA PUESTA A PRODUCCION DEL SIGHOV2 ETAPA 2)

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => 0,
                'stock_actualizado' => $cantidad,
                'nro_lote' => 1,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idUnidadFarmacia,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => $nuevoMedicamentoStock->id,
                'id_movimiento' => null,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);

            //FIN DE MEDICAMENTOS STOCK

            //PARA MOVIMIENTOS

            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $idCierreGestion,
                'id_identificador_tabla' => 4,
                'dato_solicitante' => 'FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => Carbon::now(),
                'dato_interesado' => 'CIERRE DE GESTION - ' . Carbon::now()->format('Y'),
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $cantidad,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idUnidadFarmacia,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => null,
                'id_movimiento' => $nuevoMovimiento->id,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);

            //FIN DE MOVIMIENTOS
        }
    }

    public function procesarCierreGestionSinDiferencia($idMedicamento, $fechaVencimiento, $cantidad, $idCierreGestion)
    {
        $medicamentoStock = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $this->idUnidadFarmacia)
            ->orderBy('nro_lote', 'desc')
            ->first();

        if ($medicamentoStock) {
            $medicamentoStock->cierre_gestion = true;
            $medicamentoStock->fecha_vencimiento = $fechaVencimiento;
            $medicamentoStock->save();

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => $medicamentoStock->id,
                'id_movimiento' => null,
                'cantidad' => null,
                'tipo_movimiento' => 'SIN DIFERENCIA',
            ]);
        } else {
            //PARA MEDICAMENTOS STOCK EN CIERRE DE GESTION (LOGICA FUNCIONAL UNICAMENTE PARA LA PUESTA A PRODUCCION DEL SIGHOV2 ETAPA 2)

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => 0,
                'stock_actualizado' => $cantidad,
                'nro_lote' => 1,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idUnidadFarmacia,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => $nuevoMedicamentoStock->id,
                'id_movimiento' => null,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);

            //FIN DE MEDICAMENTOS STOCK
        }

        $movimiento = Movimiento::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $this->idUnidadFarmacia)
            ->orderBy('id', 'desc')
            ->first();

        if ($movimiento) {
            $movimiento->cierre_gestion = true;
            $movimiento->fecha_vencimiento = $fechaVencimiento;
            $movimiento->observacion = 'CIERRE DE GESTIÓN CORRESPONDIENTE A LA GESTIÓN ' . Carbon::now('Y');
            $movimiento->save();

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => null,
                'id_movimiento' => $movimiento->id,
                'cantidad' => null,
                'tipo_movimiento' => 'SIN DIFERENCIA',
            ]);
        } else {
            //PARA MOVIMIENTOS

            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $idCierreGestion,
                'id_identificador_tabla' => 4,
                'dato_solicitante' => 'FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => Carbon::now(),
                'dato_interesado' => 'CIERRE DE GESTION - ' . Carbon::now()->format('Y'),
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $cantidad,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idUnidadFarmacia,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idUnidadFarmacia,
                'id_medicamento_stock' => null,
                'id_movimiento' => $nuevoMovimiento->id,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);

            //FIN DE MOVIMIENTOS
        }
    }
}
