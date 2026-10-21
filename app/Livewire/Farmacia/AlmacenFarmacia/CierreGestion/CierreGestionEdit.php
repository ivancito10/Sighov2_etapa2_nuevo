<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\CierreGestion;

use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Carbon;

use App\Models\Farmacia\Movimiento;
use App\Models\Administracion\Persona;
use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\ControlCierreGestion;
use App\Models\Farmacia\MedicamentoStock;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;

class CierreGestionEdit extends Component
{

    public $id_cierre,
        $cantidad_fisica,
        $cantidad_sistema,
        $fecha_registro,
        $diferencia,
        $id_unidad,
        $id_medicamento,
        $created_at,
        $updated_at,
        $id_user_created,
        $id_user_updated,
        $codigo_medicamento,
        $nombre_medicamento,
        $fecha_vencimiento,
        $fecha_vencimiento_baja,
        $forma_farmaceutica,
        $concentracion, $cantidad_bajas, $stock_actual, $mostrarDiferencia, $diferencia_mostrar;
    public $cod_medicamento, $medicamento;
    //datos persona
    public $unidadInventariador, $cargoInventariador, $nombre, $nombre_completo, $carnet, $unidadSolicitante, $cargoSolicitante;
    public $idCierreGestionRegistrado;
    public $idAlmacenFarmacia;

    public $opcionesBajaMedicamento;

    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-edit');
    }

    public function mount()
    {
        $this->idAlmacenFarmacia = $this->obtenerIdAlmacen();
        $this->opcionesBajaMedicamento = false;
    }

    #[On('editarCierreAlmacen')]
    public function editarCierreAlmacen($data)
    {
        //dd('asdsa', $data);
        //$this->id_cierre = $data;
        $this->mostrarCierreAlmacen($data);
        $this->dispatch('editarCierreAlmacenFarmacia');
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    public function mostrarCierreAlmacen($id)
    {
        //dd('adasd', $this->cantidad_fisica, $this->cantidad_sistema);
        $cierre = CierreGestion::with(['medicamentoCierreGestion.detalleIngresoMedicamento'])->find($id);
        // dd($cierre);
        $this->id_cierre = $cierre->id;
        $this->cantidad_fisica = $cierre->cantidad_fisica;
        $this->cantidad_sistema = $cierre->cantidad_sistema;
        $this->cantidad_bajas = $cierre->cantidad_bajas;
        $this->fecha_registro = $cierre->fecha_registro;
        $this->fecha_vencimiento = Carbon::parse($cierre->fecha_vencimiento)->Format('Y-m-d');
        if ($this->fecha_vencimiento_baja) {
            $this->fecha_vencimiento_baja = Carbon::parse($cierre->fecha_vencimiento_baja)->Format('Y-m-d');
        } else {
            $this->fecha_vencimiento_baja = null;
        }
        // dd('adasd', $this->cantidad_fisica, $this->cantidad_sistema);
        $this->diferencia = $cierre->diferencia;
        $this->diferencia_mostrar = abs($this->diferencia);
        $this->id_unidad = $cierre->id_unidad;
        $this->id_medicamento = $cierre->id_medicamento;
        $this->created_at = $cierre->created_at;
        $this->updated_at = $cierre->updated_at;
        $this->id_user_created = $cierre->id_user_created;
        $this->id_user_updated = $cierre->id_user_updated;
        $this->codigo_medicamento = $cierre->medicamentoCierreGestion->codigo_medicamento;
        $this->nombre_medicamento = $cierre->medicamentoCierreGestion->nombre;
        $this->forma_farmaceutica = $cierre->medicamentoCierreGestion->forma_farmaceutica;
        $this->concentracion = $cierre->medicamentoCierreGestion->concentracion;
        //datos persona
        $personaData = $this->obtenerDatosPersona($cierre->usuarioCreador->id_persona);
        $this->unidadInventariador = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoInventariador = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        $persona = Persona::where('id', $cierre->usuarioCreador->id_persona)->first();
        $this->nombre = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
        $this->carnet = $persona->ci;

        $stock = Movimiento::where('id_medicamento', $this->id_medicamento)
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->orderBy('id', 'desc')
            ->first();
        //dd($stock);
        $this->stock_actual = $stock->saldo ?? 0;
        //dd($stock);
    }

    public function procesarCantidad()
    {
        // dd('adasd', $this->cantidad_fisica, $this->cantidad_sistema);
        if ($this->cantidad_fisica != null) {
            $this->diferencia = $this->cantidad_sistema - $this->cantidad_fisica;
            //  dd('aqui 1.2', $this->diferencia);
            $this->diferencia_mostrar = abs($this->diferencia);
            //  dd('aqui 1', $this->diferencia);
        } else {
            $this->cantidad_fisica = 0;
            $this->diferencia =  $this->cantidad_sistema - $this->cantidad_fisica;
            //  dd('aqui 2', $this->diferencia);
            $this->diferencia_mostrar = abs($this->diferencia);
        }
    }

    /*public function rules()
    {
        return [
            // dd('asdas'),
            'cantidad_fisica' => 'required | gt:0',
            'cantidad_bajas' => 'required_with:fecha_vencimiento_baja',
            'diferencia_mostrar' => 'required',
            'fecha_vencimiento' => 'required|date',
            'fecha_vencimiento_baja' => 'required_with:cantidad_bajas'
        ];
    }*/

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

    #[On('actualizarCierreAlmacenFarmacia')]
    public function actualizarCierreAlmacenFarmacia()
    {
        $this->procesarCantidad();

        $ultimoMovimiento = Movimiento::where('id_medicamento', $this->id_medicamento)
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimoMovimiento) {
            if ($ultimoMovimiento->cierre_gestion) {
                if (trim($this->fecha_vencimiento) === '') {
                    $this->fecha_vencimiento = null;
                }

                if (trim($this->fecha_vencimiento_baja) === '') {
                    $this->fecha_vencimiento_baja = null;
                }

                if (trim($this->cantidad_bajas) === "") {
                    $this->cantidad_bajas = null;
                }
                //  dd('ya pasa a editar' , $this->cantidad_fisica,$this->diferencia, $this->cantidad_bajas , $this->fecha_vencimiento_baja, $this->fecha_vencimiento);
                $this->validate();
                try {

                    $existeCierre = CierreGestion::find($this->id_cierre);
                    // dd($existeCierre);
                    if ($existeCierre) {

                        if ($existeCierre->diferencia > 0) {
                            $this->revertirSalida($this->id_cierre);
                        } elseif ($existeCierre->diferencia === 0) {
                            $this->revertirSinDiferencia($this->id_cierre);
                        } else {
                            $this->revertirIngreso($this->id_cierre);
                        }

                        $existeCierre->update([
                            'cantidad_fisica' => $this->cantidad_fisica,
                            'diferencia' => $this->diferencia,
                            'cantidad_bajas'  => $this->cantidad_bajas,
                            'fecha_vencimiento' => $this->fecha_vencimiento,
                            'fecha_vencimiento_baja' => $this->fecha_vencimiento_baja,
                            'id_user_updated' => auth()->user()->id,
                        ]);

                        if ($this->diferencia > 0) {
                            //dd('la diferencia salio mayor que 0'); //SIGNIFICA QUE HAY MÁS EN SISTEMA QUE EN FÍSICO ---> Procede: SALIDA
                            $this->obtencionDatosMovimientos($this->id_cierre, $this->id_medicamento, abs($this->diferencia), $this->fecha_vencimiento, 1);
                        } elseif ($this->diferencia === 0) {
                            //dd('la diferencia salio 0'); //SIGNIFICA QUE NO EXISTE DIFERENCIA, ENTONCES NO SE HACER NADA MÁS QUE ACTUALIZAR EL ESTADO DE CIERRE_GESTION A TRUE ---> Solo se actualiza el último registro
                            $this->obtencionDatosMovimientos($this->id_cierre, $this->id_medicamento, abs($this->diferencia), $this->fecha_vencimiento, 2);
                        } else {
                            //dd('la diferencia salio menor que 0'); //SIGNIFICA QUE HAY MÁS FISICO QUE EN SISTEMA ---> Se realiza un: INGRESO
                            $this->obtencionDatosMovimientos($this->id_cierre, $this->id_medicamento, abs($this->diferencia), $this->fecha_vencimiento, 3);
                        }
                    }
                    $this->dispatch('pg:eventRefresh-CierreAlmacenFarmaciaTable');
                    $this->dispatch('actualizarCierreAlmacenFarmaciaExitoso');
                } catch (\Throwable $th) {
                    //throw $th;
                }
            } else {
                $this->dispatch('existenMovimientosPosteriores');
            }
        }
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

            //$this->revertirSalida($idCierreGestion);

            $datosNecesariosParaMovimientosSalida = [
                'id_corresponde' => $idCierreGestion,
                'id_unidad' => $this->idAlmacenFarmacia,
                'nro_documento' => 'CIERRE-GESTION-' . Carbon::now()->format('Y'),
            ];

            $this->procesarCierreGestionSalida($idMedicamento, $cantidad, $datosNecesariosParaMovimientosSalida, $fechaVencimiento);
        } elseif ($tipoDiferencia === 2) {
            //$this->revertirSinDiferencia($idCierreGestion);
            $this->procesarCierreGestionSinDiferencia($idMedicamento, $fechaVencimiento, $cantidad, $idCierreGestion);
        } elseif ($tipoDiferencia === 3) {
            //$this->revertirIngreso($idCierreGestion);
            $this->procesarCierreGestionIngreso($idMedicamento, $cantidad, $fechaVencimiento, $idCierreGestion);
        }
    }

    public function procesarCierreGestionSalida(int $idMedicamento, int $cantidad, array $datosNecesarios, $fechaVencimiento)
    {
        $lotes = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->where('stock_actualizado', '>', 0)
            //->where('fecha_vencimiento', '>', Carbon::now())
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
                'dato_interesado' => 'ALMACEN DE FARMACIA',
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
                'id_cierre_gestion' => $this->id_cierre,
                'id_unidad' => $this->idAlmacenFarmacia,
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
            'id_cierre_gestion' => $this->id_cierre,
            'id_unidad' => $this->idAlmacenFarmacia,
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
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->max('nro_lote');

        if ($maxNroLote) {
            $ultimoPrecioUnitario = MedicamentoStock::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $this->idAlmacenFarmacia)
                ->where('nro_lote', $maxNroLote)
                ->first();

            $nroLotePorMedicamento = $maxNroLote ? $maxNroLote + 1 : 1;

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => $ultimoPrecioUnitario->precio_unitario,
                'stock_actualizado' => $cantidad,
                'nro_lote' => $nroLotePorMedicamento,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idAlmacenFarmacia,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->id_cierre,
                'id_unidad' => $this->idAlmacenFarmacia,
                'id_medicamento_stock' => $nuevoMedicamentoStock->id,
                'id_movimiento' => null,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);

            // FIN DE LA INSERCION EN LA TABLA MEDICAMENTOS STOCK -----------------------------------------------------

            //PARA LA TABLA MOVIMIENTO

            /*$movimientoPrevio = Movimiento::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $this->idAlmacenFarmacia)
                //->where('nro_lote', $this->nro_lote)
                ->orderBy('fecha', 'desc')
                ->first();*/

            $saldoMedStock = MedicamentoStock::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $this->idAlmacenFarmacia)
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
                'dato_solicitante' => 'ALMACEN DE FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => Carbon::now(),
                'dato_interesado' => 'CIERRE DE GESTION - ' . Carbon::now()->format('Y'),
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $saldo,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idAlmacenFarmacia,
                'nro_lote' => $nroLotePorMedicamento,
                'precio_unitario_peps' => $ultimoPrecioUnitario->precio_unitario,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->id_cierre,
                'id_unidad' => $this->idAlmacenFarmacia,
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
                'id_unidad' => $this->idAlmacenFarmacia,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->id_cierre,
                'id_unidad' => $this->idAlmacenFarmacia,
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
                'dato_solicitante' => 'ALMACEN DE FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => Carbon::now(),
                'dato_interesado' => 'CIERRE DE GESTION - ' . Carbon::now()->format('Y'),
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $cantidad,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idAlmacenFarmacia,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->id_cierre,
                'id_unidad' => $this->idAlmacenFarmacia,
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
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->orderBy('nro_lote', 'desc')
            ->first();

        if ($medicamentoStock) {
            $medicamentoStock->cierre_gestion = true;
            $medicamentoStock->fecha_vencimiento = $fechaVencimiento;
            $medicamentoStock->save();

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->id_cierre,
                'id_unidad' => $this->idAlmacenFarmacia,
                'id_medicamento_stock' => $medicamentoStock->id,
                'id_movimiento' => null,
                'cantidad' => null,
                'tipo_movimiento' => 'SIN DIFERENCIA',
            ]);
        } else {
            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => 0,
                'stock_actualizado' => $cantidad,
                'nro_lote' => 1,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idAlmacenFarmacia,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);

            $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idAlmacenFarmacia,
                'id_medicamento_stock' => $nuevoMedicamentoStock->id,
                'id_movimiento' => null,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);
        }

        $movimiento = Movimiento::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->orderBy('fecha', 'desc')
            ->first();

        if ($movimiento) {
            $movimiento->cierre_gestion = true;
            $movimiento->fecha_vencimiento = $fechaVencimiento;
            $movimiento->observacion = $movimiento->observacion . ' CIERRE DE GESTIÓN CORRESPONDIENTE A LA GESTIÓN ' . Carbon::now('Y');
            $movimiento->save();

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->id_cierre,
                'id_unidad' => $this->idAlmacenFarmacia,
                'id_medicamento_stock' => null,
                'id_movimiento' => $movimiento->id,
                'cantidad' => null,
                'tipo_movimiento' => 'SIN DIFERENCIA',
            ]);
        } else {
            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $idCierreGestion,
                'id_identificador_tabla' => 4,
                'dato_solicitante' => 'ALMACEN DE FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => Carbon::now(),
                'dato_interesado' => 'CIERRE DE GESTION - ' . Carbon::now()->format('Y'),
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $cantidad,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $this->idAlmacenFarmacia,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
                'id_unidad' => $this->idAlmacenFarmacia,
                'id_medicamento_stock' => null,
                'id_movimiento' => $nuevoMovimiento->id,
                'cantidad' => $cantidad,
                'tipo_movimiento' => 'INGRESO',
            ]);
        }
    }

    public function revertirSalida($idCierreGestion)
    {
        $salidasARevertir = ControlCierreGestion::where('id_cierre_gestion', $idCierreGestion)
            ->where('tipo_movimiento', 'SALIDA')
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->where('revertido', false)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($salidasARevertir as $salida) {
            if ($salida->id_medicamento_stock !== null) {
                $medicamentoStockARevertir = MedicamentoStock::find($salida->id_medicamento_stock);
                if ($medicamentoStockARevertir) {
                    $medicamentoStockARevertir->stock_actualizado = $medicamentoStockARevertir->stock_actualizado + $salida->cantidad;
                    $medicamentoStockARevertir->cierre_gestion = false;
                    $medicamentoStockARevertir->save();
                }
            } else {
                $movimientoARevertir = Movimiento::find($salida->id_movimiento);
                if ($movimientoARevertir) {
                    $movimientoARevertir->delete();
                }
            }
            $salida->revertido = true;
            $salida->save();
        }
    }

    public function revertirIngreso($idCierreGestion)
    {
        $ingresosARevertir = ControlCierreGestion::where('id_cierre_gestion', $idCierreGestion)
            ->where('tipo_movimiento', 'INGRESO')
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->where('revertido', false)
            ->orderBy('id', 'asc')
            ->get();

        //dd($ingresosARevertir);

        foreach ($ingresosARevertir as $ingreso) {
            if ($ingreso->id_medicamento_stock !== null) {
                $medicamentoStockARevertir = MedicamentoStock::find($ingreso->id_medicamento_stock);
                if ($medicamentoStockARevertir) {
                    $medicamentoStockARevertir->delete();
                }
            } else {
                $movimientoARevertir = Movimiento::find($ingreso->id_movimiento);
                if ($movimientoARevertir) {
                    $movimientoARevertir->delete();
                }
            }

            $ingreso->revertido = true;
            $ingreso->save();
        }
    }

    public function revertirSinDiferencia($idCierreGestion)
    {
        $sinDiferenciasARevertir = ControlCierreGestion::where('id_cierre_gestion', $idCierreGestion)
            //->where('tipo_movimiento', 'SIN DIFERENCIA')
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->where('revertido', false)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($sinDiferenciasARevertir as $sinDiferencia) {

            if ($sinDiferencia->tipo_movimiento === 'SIN DIFERENCIA') {
                if ($sinDiferencia->id_medicamento_stock !== null) {
                    $medicamentoStockARevertir = MedicamentoStock::find($sinDiferencia->id_medicamento_stock);
                    if ($medicamentoStockARevertir) {
                        $medicamentoStockARevertir->cierre_gestion = false;
                        $medicamentoStockARevertir->save();
                    }
                } else {
                    $movimientoARevertir = Movimiento::find($sinDiferencia->id_movimiento);
                    if ($movimientoARevertir) {
                        $movimientoARevertir->cierre_gestion = false;
                        $movimientoARevertir->observacion = null;
                        $movimientoARevertir->save();
                    }
                }
                $sinDiferencia->revertido = true;
                $sinDiferencia->save();
            }

            if ($sinDiferencia->tipo_movimiento === 'INGRESO') {
                if ($sinDiferencia->id_medicamento_stock !== null) {
                    $medicamentoStockARevertir = MedicamentoStock::find($sinDiferencia->id_medicamento_stock);
                    if ($medicamentoStockARevertir) {
                        $medicamentoStockARevertir->delete();
                    }
                } else {
                    $movimientoARevertir = Movimiento::find($sinDiferencia->id_movimiento);
                    if ($movimientoARevertir) {
                        $movimientoARevertir->delete();
                    }
                }

                $sinDiferencia->revertido = true;
                $sinDiferencia->save();
            }
        }
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    public function camcelarCierreEditar()
    {
        $this->dispatch('cierre_cancelar_edit');
        // return redirect()->route('farmacia.almacen-farmacia.cierre-gestion.index');
    }
}
