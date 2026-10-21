<?php

namespace App\Services;

use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use Carbon\Carbon;

class AjusteMovimientosPostInventario
{
    //ESTE SERVICIO UNICAMENTE TIENE UTILIDAD PARA CORREGIR LOS STOCK DE LOS MEDICAMENTO QUE 
    //TUVIERON REVERSIONES DESPUÉS DEL INVENTARIO DEL PASADO 14 DE FEBRERO DE 2026  

    //PARA LOS CASOS DE FARMACIA
    //CASO 1
    public function corregirRecetasCaducadas()
    {

        $cierres = CierreGestion::whereBetween('fecha_registro', ['2026-03-14 00:00:00', '2026-03-14 23:59:00'])
            //->where('id_medicamento', 60)
            ->where('id_unidad', 76)
            ->get();

        //dd($cierres);
        //$count = 0;
        foreach ($cierres as $key => $cierre) {
            $fechaInicioCierre = Carbon::parse($cierre->fecha_registro);
            $fechaFinCierre = Carbon::parse($cierre->fecha_registro)->addDays(2);
            //dd($fechaInicioCierre, $fechaFinCierre, $cierre->fecha_registro);
            $movimientos = Movimiento::where('id_unidad', 76)
                ->where('id_medicamento', $cierre->id_medicamento)
                ->where('created_at', '>', $fechaInicioCierre)
                ->where('created_at', '<', $fechaFinCierre)
                ->where('id_identificador_tabla', 3)
                ->where('id_estado_validacion', 4)
                ->get();

            //dd($movimientos);

            foreach ($movimientos as $key => $movimiento) {
                /*if ($movimiento->id_medicamento === 60) {
                    $count++;
                }*/
                $datosNecesarios = [
                    'id_corresponde' => $movimiento->id_corresponde,
                    'id_unidad' => 76,
                    'nro_documento' => $movimiento->nro_documento,
                    'datos_especialista' => $movimiento->dato_interesado,
                    'datos_paciente' => $movimiento->dato_solicitante
                ];

                $this->procesarLotesEntrega($movimiento->id_medicamento, $movimiento->cantidad_ingreso, $datosNecesarios);
                $movimiento->stock_real = true;
                $movimiento->save();
            }
        }
        //dd($count);
    }

    private function procesarLotesEntrega(int $idMedicamento, int $cantidad, array $datosNecesarios)
    {
        $lotes = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->where('stock_actualizado', '>', 0)
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

            /*$datosEspecialista = collect($this->datos_medico)
                ->firstWhere('id', $this->id_especialista['value']);*/
            /*$nombreEspecialista = implode(' ', array_filter([
                $datosEspecialista['persona_especialista']['p_apellido'],
                $datosEspecialista['persona_especialista']['s_apellido'],
                $datosEspecialista['persona_especialista']['nombres'],
            ]));*/

            $nombreEspecialista = $datosNecesarios['datos_especialista'];

            $datosParaMovimientos = [
                'id_corresponde' => $datosNecesarios['id_corresponde'],
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $datosNecesarios['id_unidad'],
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $datosNecesarios['datos_paciente'],
                'nro_documento' => $datosNecesarios['nro_documento'],
                'dato_interesado' => $datosNecesarios['datos_especialista'],
                'nro_lote' => $lote->nro_lote,
                'precio_unitario_peps' => $lote->precio_unitario,
                'fecha_vencimiento' => $lote->fecha_vencimiento,
            ];

            $lote->stock_actualizado = $disponible - $cantidadARestar;
            $lote->save();

            $cantidad -= $cantidadARestar;


            $this->registrarMovimientoSolicitudUnidadAFarmacia($datosParaMovimientos);
        }
    }

    public function registrarMovimientoSolicitudUnidadAFarmacia($datosNecesarios)
    {
        //PARA LA TABLA DE MOVIMIENTOS 
        $movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->orderBy('id', 'desc')
            ->first();
        $nuevoSaldo = $movimientoAnterior->saldo - $datosNecesarios['cantidad'];


        $nuevoMovimiento = Movimiento::create([
            'id_corresponde' => $datosNecesarios['id_corresponde'],
            'id_identificador_tabla' => 3,
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
            'stock_real' => true,
            'observacion' => 'IGUALACION POR CIERRE DE GESTION'
        ]);

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }


    //CASO 2 PARA LAS RECETAS ENTREGADAS

    public function corregirRecetasEntregadas()
    {

        $cierres = CierreGestion::whereBetween('fecha_registro', ['2026-03-14 00:00:00', '2026-03-14 23:59:00'])
            //->where('id_medicamento', 60)
            ->where('id_unidad', 76)
            ->get();

        //dd($cierres);

        foreach ($cierres as $key => $cierre) {
            $movimientos = Movimiento::where('id_unidad', 76)
                ->where('movimiento.id_medicamento', $cierre->id_medicamento)
                ->where('receta.updated_at', '>', $cierre->fecha_registro)
                ->where('movimiento.created_at', '<', $cierre->fecha_registro)
                ->where('movimiento.id_identificador_tabla', 3)
                ->where('movimiento.id_estado_validacion', 2)
                ->join('consulta_externa.receta as receta', function ($join) {
                    $join->on('movimiento.id_corresponde', '=', 'receta.id');
                })
                ->get();

            //dd($movimientos);

            foreach ($movimientos as $key => $movimiento) {
                $datosNecesarios = [
                    'id_corresponde' => $movimiento->id_corresponde,
                    'id_unidad' => 76,
                    'nro_documento' => $movimiento->nro_documento,
                    'datos_especialista' => $movimiento->dato_interesado,
                    'datos_paciente' => $movimiento->dato_solicitante
                ];

                $this->procesarLotesEntrega($movimiento->id_medicamento, $movimiento->cantidad_salida, $datosNecesarios);
                $movimiento->stock_real = true;
                $movimiento->save();
            }
        }
    }





















































    //CASO DE ALMACEN
    public function corregirCierresAlmacen()
    {
        $medicamentosConMovimiento = [];
        $cierresAlmacen = CierreGestion::where('id_unidad', 89)
            ->whereBetween('fecha_registro', ['2026-03-14 00:00:00', '2026-03-15 00:00:00'])
            //->where('id', 2750)
            ->get();

        foreach ($cierresAlmacen as $key => $cierreAlmacen) {

            $stockActual = MedicamentoStock::where('id_unidad', 89)->where('id_medicamento', $cierreAlmacen->id_medicamento)->sum('stock_actualizado');

            $diferenciaNueva = $stockActual - $cierreAlmacen->cantidad_fisica;

            //dd($diferenciaNueva, $stockActual, $cierreAlmacen->cantidad_fisica);

            if ($cierreAlmacen) {
                if ($diferenciaNueva > 0) {
                    //dd('la diferencia salio mayor que 0'); //SIGNIFICA QUE HAY MÁS EN SISTEMA QUE EN FÍSICO ---> Procede: SALIDA
                    $this->obtencionDatosMovimientos($cierreAlmacen->id, $cierreAlmacen->id_medicamento, abs($diferenciaNueva), $cierreAlmacen->fecha_vencimiento, 1);
                } elseif ($diferenciaNueva === 0) {
                    //dd('la diferencia salio 0'); //SIGNIFICA QUE NO EXISTE DIFERENCIA, ENTONCES NO SE HACER NADA MÁS QUE ACTUALIZAR EL ESTADO DE CIERRE_GESTION A TRUE ---> Solo se actualiza el último registro
                    $this->obtencionDatosMovimientos($cierreAlmacen->id, $cierreAlmacen->id_medicamento, abs($diferenciaNueva), $cierreAlmacen->fecha_vencimiento, 2);
                } else {
                    //dd('la diferencia salio menor que 0'); //SIGNIFICA QUE HAY MÁS FISICO QUE EN SISTEMA ---> Se realiza un: INGRESO
                    $this->obtencionDatosMovimientos($cierreAlmacen->id, $cierreAlmacen->id_medicamento, abs($diferenciaNueva), $cierreAlmacen->fecha_vencimiento, 3);
                }
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
            $datosNecesariosParaMovimientosSalida = [
                'id_corresponde' => $idCierreGestion,
                'id_unidad' => 89,
                'nro_documento' => 'CIERRE-GESTION-ALMACEN-FARMACIA' . Carbon::now()->format('Y'),
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

            $cantidad -= $cantidadARestar;

            $this->registrarMovimientoSalida($datosParaMovimientos);
        }
    }

    public function procesarCierreGestionSinDiferencia($idMedicamento, $fechaVencimiento, $cantidad, $idCierreGestion)
    {
        $medicamentoStock = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', 89)
            ->orderBy('nro_lote', 'desc')
            ->first();

        if ($medicamentoStock) {
            $medicamentoStock->cierre_gestion = true;
            $medicamentoStock->fecha_vencimiento = $fechaVencimiento;
            $medicamentoStock->save();
        } else {
            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => 0,
                'stock_actualizado' => $cantidad,
                'nro_lote' => 1,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => 89,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);
        }

        $movimiento = Movimiento::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', 89)
            ->orderBy('fecha', 'desc')
            ->first();

        if ($movimiento) {
            $movimiento->cierre_gestion = true;
            $movimiento->fecha_vencimiento = $fechaVencimiento;
            $movimiento->observacion = 'CIERRE DE GESTIÓN CORRESPONDIENTE A LA GESTIÓN ' . Carbon::now('Y');
            $movimiento->save();
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
                'id_unidad' => 89,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);
        }
    }

    public function procesarCierreGestionIngreso($idMedicamento, $cantidad, $fechaVencimiento, $idCierreGestion)
    {
        $maxNroLote = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', 89)
            ->max('nro_lote');

        if ($maxNroLote) {
            $ultimoPrecioUnitario = MedicamentoStock::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', 89)
                ->where('nro_lote', $maxNroLote)
                ->first();

            $nroLotePorMedicamento = $maxNroLote ? $maxNroLote + 1 : 1;

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => $ultimoPrecioUnitario->precio_unitario,
                'stock_actualizado' => $cantidad,
                'nro_lote' => $nroLotePorMedicamento,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => 89,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
            ]);

            // FIN DE LA INSERCION EN LA TABLA MEDICAMENTOS STOCK -----------------------------------------------------

            //PARA LA TABLA MOVIMIENTO

            $movimientoPrevio = Movimiento::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', 89)
                //->where('nro_lote', $this->nro_lote)
                ->orderBy('id', 'desc')
                ->first();

            $saldo = 0;

            $medStockAjuste = MedicamentoStock::where('id_unidad', 89)->where('id_medicamento', $idMedicamento)->sum('stock_actualizado');

            if ($medStockAjuste) {
                //DADO QUE ES UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                $saldo = $cantidad + $medStockAjuste;
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
                'id_unidad' => 89,
                'nro_lote' => $nroLotePorMedicamento,
                'precio_unitario_peps' => $ultimoPrecioUnitario->precio_unitario,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);
        } else {

            //PARA MEDICAMENTOS STOCK EN CIERRE DE GESTION (LOGICA FUNCIONAL UNICAMENTE PARA LA PUESTA A PRODUCCION DEL SIGHOV2 ETAPA 2)

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => 0,
                'stock_actualizado' => $cantidad,
                'nro_lote' => 1,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => 89,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => auth()->user()->id,
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
                'id_unidad' => 89,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => auth()->user()->id,
                'cierre_gestion' => true,
                'stock_real' => true,
            ]);

            //FIN DE MOVIMIENTOS
        }
    }

    public function registrarMovimientoSalida($datosNecesarios)
    {
        //PARA LA TABLA DE MOVIMIENTOS 
        $movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->orderBy('id', 'desc')
            ->first();

        $medStockAjuste = MedicamentoStock::where('id_unidad', 89)->where('id_medicamento', $datosNecesarios['id_medicamento'])->sum('stock_actualizado');

        $nuevoSaldo = $medStockAjuste - $datosNecesarios['cantidad'];

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

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }
}
