<?php

namespace App\Services\Farmacia;

use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Services\ListaUnidades;
use Carbon\Carbon;

class AnularRecetasService
{
    /*public function mostrarRecetasCaducas()
    {
        $fechaLimite = Carbon::now()->subDays(2);
        $recetasCaducadas = Receta::where('created_at', '<=', $fechaLimite)
            ->whereNotIn('id_estado_validacion', [2, 3, 4])
            ->get();

        dd($recetasCaducadas);
        //dd($fechaLimite);   
        /*foreach ($recetasCaducadas as $key => $recetaCaducada) {
            $recetaCaducada->id_estado_validacion = 4;
            $recetaCaducada->save();
        }
    }*/


    public function revertirStocksRecetasAnuladas($idReceta, $esAnulacion)
    {
        //dd('entra');
        $recetaARevertir = Receta::find($idReceta);
        $medicamentosRecetaARevertir = RecetaMedicamento::where('id_receta', $idReceta)->get();


        //dd($recetaARevertir, $medicamentosRecetaARevertir);

        if (count($medicamentosRecetaARevertir) > 0) {
            foreach ($medicamentosRecetaARevertir as $key => $medicamentoReceta) {

                $ultimoCierreGestion = Movimiento::where('id_identificador_tabla', 4)
                    ->where('id_medicamento',$medicamentoReceta->id_medicamento)
                    ->orderBy('id', 'desc')
                    ->first();

                $fechaUltimoCierre = $ultimoCierreGestion->created_at;
                //dd($fechaUltimoCierre, $recetaARevertir->created_at, $fechaUltimoCierre->lt($recetaARevertir->created_at));
                if ($fechaUltimoCierre->lt($recetaARevertir->created_at)) {
                    $movimientosARevertir = Movimiento::where('id_corresponde', $idReceta)
                        ->where('id_identificador_tabla', 3)
                        //->where('nro_documento', $recetaARevertir->nro_receta)
                        ->where('id_medicamento', $medicamentoReceta->id_medicamento)
                        ->where('id_unidad', 76)
                        ->get();
                    //dd($movimientosARevertir);

                    foreach ($movimientosARevertir as $movimientoARevertir) {
                        //$movimientoPrevio = Movimiento::where('id_medicamento', $medicamentoReceta->id_medicamento)
                        //    ->where('id_unidad', 76)
                        //    ->orderBy('id', 'desc')
                        //    ->first();

                        $saldoMedStock = MedicamentoStock::where('id_medicamento', $medicamentoReceta->id_medicamento)
                            ->where('id_unidad', 76)
                            ->sum('stock_actualizado');

                        $saldo = 0;

                        if ($saldoMedStock) {
                            //DADO QUE ES UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                            $saldo = $movimientoARevertir->cantidad_salida + $saldoMedStock;
                        } else {
                            $saldo = $movimientoARevertir->cantidad_salida;
                        }

                        $observacion = $esAnulacion ? 'RECETA ANULADA REVERTIDA' : 'RECETA CADUCADA REVERTIDA';
                        $idEstadoValidacion = $esAnulacion ? 3 : 4;

                        $stockReal = false;

                        if ($recetaARevertir->id_estado_validacion === 2 && $esAnulacion) {
                            $stockReal = true;
                        }

                        $nuevoMovimiento = Movimiento::create([
                            'id_corresponde' => $movimientoARevertir->id_corresponde,
                            'id_identificador_tabla' => $movimientoARevertir->id_identificador_tabla,
                            'dato_solicitante' => $movimientoARevertir->dato_solicitante,
                            'nro_documento' => $movimientoARevertir->nro_documento,
                            'fecha' => Carbon::now(),
                            'dato_interesado' => $movimientoARevertir->dato_interesado,
                            'cantidad_ingreso' => $movimientoARevertir->cantidad_salida,
                            'cantidad_salida' => 0,
                            'saldo' => $saldo,
                            'id_estado_validacion' => $idEstadoValidacion,
                            'id_medicamento' => $movimientoARevertir->id_medicamento,
                            'id_unidad' => $movimientoARevertir->id_unidad,
                            'nro_lote' => $movimientoARevertir->nro_lote,
                            'precio_unitario_peps' => $movimientoARevertir->precio_unitario_peps,
                            'id_user_created' => auth()->user()->id,
                            'fecha_vencimiento' => $movimientoARevertir->fecha_vencimiento,
                            'observacion' => $observacion,
                            'stock_real' => $stockReal,
                        ]);

                        $medicamentoStockARevertir = MedicamentoStock::where('id_medicamento', $movimientoARevertir->id_medicamento)
                            ->where('id_unidad', 76)
                            ->where('nro_lote', $movimientoARevertir->nro_lote)
                            ->first();

                        if ($medicamentoStockARevertir) {
                            $medicamentoStockARevertir->stock_actualizado = $medicamentoStockARevertir->stock_actualizado + $movimientoARevertir->cantidad_salida;
                            $medicamentoStockARevertir->id_user_updated = auth()->user()->id;
                            $medicamentoStockARevertir->save();
                        }
                    }
                } else {
                    $movimientosARevertir = Movimiento::where('id_corresponde', $idReceta)
                        ->where('id_identificador_tabla', 3)
                        ->where('id_medicamento', $medicamentoReceta->id_medicamento)
                        ->where('id_unidad', 76)
                        ->get();

                    foreach ($movimientosARevertir as $movimientoARevertir) {
                        $observacion = $esAnulacion ? 'RECETA ANULADA REVERTIDA' : 'RECETA CADUCADA REVERTIDA';
                        $idEstadoValidacion = $esAnulacion ? 3 : 4;

                        $stockReal = false;

                        if ($recetaARevertir->id_estado_validacion === 2 && $esAnulacion) {
                            $stockReal = true;
                        }

                        $movimientoARevertir->observacion = $observacion;
                        $movimientoARevertir->stock_real = $stockReal;
                        $movimientoARevertir->id_estado_validacion = $idEstadoValidacion;
                        $movimientoARevertir->id_user_updated = auth()->user()->id;
                        $movimientoARevertir->save();
                    }
                }
            }
        }
    }

    public function revertirRecetasAnuladas($idReceta, $esAnulacion)
    {
        $recetaARevertir = Receta::find($idReceta);
        $medicamentosRecetaARevertir = RecetaMedicamento::where('id_receta', $idReceta)->get();

        //dd($recetaARevertir, $medicamentosRecetaARevertir);

        if (count($medicamentosRecetaARevertir) > 0) {
            foreach ($medicamentosRecetaARevertir as $key => $medicamentoReceta) {

                $movimientosARevertir = Movimiento::where('id_corresponde', $idReceta)
                    ->where('id_identificador_tabla', 3)
                    ->where('id_medicamento', $medicamentoReceta->id_medicamento)
                    ->where('id_unidad', 76)
                    ->get();

                foreach ($movimientosARevertir as $movimientoARevertir) {
                    $observacion = $esAnulacion ? 'RECETA ANULADA REVERTIDA' : 'RECETA CADUCADA REVERTIDA';
                    $idEstadoValidacion = $esAnulacion ? 3 : 4;

                    $stockReal = false;

                    if ($recetaARevertir->id_estado_validacion === 2 && $esAnulacion) {
                        $stockReal = true;
                    }

                    $movimientoARevertir->observacion = $observacion;
                    $movimientoARevertir->stock_real = $stockReal;
                    $movimientoARevertir->id_estado_validacion = $idEstadoValidacion;
                    $movimientoARevertir->id_user_updated = auth()->user()->id;
                    $movimientoARevertir->save();                    
                }
            }
        }
    }

    public static function obtenerIdFarmacia()
    {
        //$datosUnidades = new ListaUnidades();
        //return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
        return 76;
    }
}
