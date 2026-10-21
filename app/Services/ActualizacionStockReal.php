<?php

namespace App\Services;

use App\Models\Farmacia\Movimiento;

class ActualizacionStockReal
{
    //1→ingreso, 2 →solicitud, 3→ receta 4→Cierre Gestion 5→Reingreso

    public function actualizarStockRealMovimiento()
    {
        $movimientos = Movimiento::where('id_identificador_tabla', 3)->where('id_unidad', 76)->get();

        foreach ($movimientos as $key => $movimiento) {
            if ($movimiento->id_estado_validacion === 1) {
                $movimiento->stock_real = false;
                $movimiento->save();
            }

            if ($movimiento->id_estado_validacion === 2) {
                $movimiento->stock_real = true;
                $movimiento->save();
            }

            if ($movimiento->id_estado_validacion === 3) {
                $movimientoRecetaPrevia = Movimiento::where('id_corresponde', $movimiento->id_corresponde)
                    ->where('id_identificador_tabla', 3)
                    ->where('id_unidad', 76)
                    ->orderBy('id', 'asc')
                    ->first();
                if ($movimientoRecetaPrevia) {
                    if ($movimientoRecetaPrevia->id_estado_validacion === 2) {
                        $movimiento->stock_real = true;
                        $movimiento->save();
                    }

                    if ($movimientoRecetaPrevia->id_estado_validacion === 1) {
                        $movimiento->stock_real = false;
                        $movimiento->save();
                    }
                }
            }

            if ($movimiento->id_estado_validacion === 4) {

                $movimientoRecetaPrevia = Movimiento::where('id_corresponde', $movimiento->id_corresponde)
                    ->where('id_identificador_tabla', 3)
                    ->where('id_unidad', 76)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($movimientoRecetaPrevia->id_estado_validacion === 1) {
                    $movimiento->stock_real = false;
                    $movimiento->save();
                }
            }
        }
    }
}
