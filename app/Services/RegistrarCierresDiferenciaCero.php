<?php

namespace App\Services;

use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\ControlCierreGestion;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use Carbon\Carbon;

class RegistrarCierresDiferenciaCero
{
    public function registrarCierres()
    {
        $cierresAlmacen = CierreGestion::where('cantidad_fisica', 0)
            ->where('cantidad_sistema', 0)
            ->where('diferencia', 0)
            ->where('id_unidad', 89)
            ->get();

        if (count($cierresAlmacen) > 0) {
            foreach ($cierresAlmacen as $key => $cierre) {
                $nuevoMedicamentoStock = MedicamentoStock::create([
                    'cantidad_inicial' => 0,
                    'precio_unitario' => 0,
                    'stock_actualizado' => 0,
                    'nro_lote' => 1,
                    'id_medicamento' => $cierre->id_medicamento,
                    'id_unidad' => $cierre->id_unidad,
                    'fecha_vencimiento' => $cierre->fecha_vencimiento,
                    'cierre_gestion' => true,
                    'id_user_created' => $cierre->id_user_created,
                ]);

                $nuevoControlCierreGestionMedStock = ControlCierreGestion::create([
                    'id_cierre_gestion' => $cierre->id,
                    'id_unidad' => $cierre->id_unidad,
                    'id_medicamento_stock' => $nuevoMedicamentoStock->id,
                    'id_movimiento' => null,
                    'cantidad' => 0,
                    'tipo_movimiento' => 'INGRESO',
                ]);

                $nuevoMovimiento = Movimiento::create([
                    'id_corresponde' => $cierre->id,
                    'id_identificador_tabla' => 4,
                    'dato_solicitante' => 'ALMACEN DE FARMACIA',
                    'nro_documento' => $cierre->id,
                    'fecha' => Carbon::now(),
                    'dato_interesado' => 'CIERRE DE GESTION - '.Carbon::now()->format('Y'),
                    'cantidad_ingreso' => 0,
                    'cantidad_salida' => 0,
                    'saldo' => 0,
                    'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                    'id_estado_validacion' => 5,
                    'id_medicamento' => $cierre->id_medicamento,
                    'id_unidad' => $cierre->id_unidad,
                    'nro_lote' => 1,
                    'precio_unitario_peps' => 0,
                    'fecha_vencimiento' => $cierre->fecha_vencimiento,
                    'id_user_created' => $cierre->id_user_created,
                    'cierre_gestion' => true,
                    'stock_real' => true,
                ]);

                $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                    'id_cierre_gestion' => $cierre->id,
                    'id_unidad' => $cierre->id_unidad,
                    'id_medicamento_stock' => null,
                    'id_movimiento' => $nuevoMovimiento->id,
                    'cantidad' => 0,
                    'tipo_movimiento' => 'INGRESO',
                ]);
            }
        }
        //dd($cierresAlmacen);
    }
}
