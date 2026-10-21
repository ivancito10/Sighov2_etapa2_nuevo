<?php

namespace App\Services;

use App\Models\ConsultaExterna\Receta;
use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\DetalleIngreso;
use App\Models\Farmacia\Ingreso;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Models\Farmacia\Reingreso;
use App\Models\Farmacia\Solicitud;
use Carbon\Carbon;

class RegenerarMovimientos
{
    public function regenerarMovimientosMedicamentoStockFarmacia($idMedicamento)
    {
        //Para Farmacia

        $idUnidadFarmacia = 76;

        //CIERRES DE GESTION PARA LA EJECUCION SERÁ 1 

        $cierresPorMedicamentoFarmacia = CierreGestion::select('id', 'updated_at')
            ->where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $idUnidadFarmacia)
            ->get();
        
        //dd($cierresPorMedicamentoFarmacia);

        //REINGRESOS PARA LA EJECUCION SERÁ 2

        $reingresosPorMedicamentoFarmacia = Reingreso::select('id', 'updated_at')
            ->whereHas('detallesMedicamento', function ($quericitoReingresos) use ($idMedicamento) {
                $quericitoReingresos->where('id_medicamento', $idMedicamento);
            })->where('id_unidad', $idUnidadFarmacia)
            ->get();

        //dd($reingresosPorMedicamentoFarmacia);

        //SOLICITUDES PARA LA EJECUCION SERÁ 3

        $solicitudesPorMedicamentoFarmacia = Solicitud::select('id', 'fecha_entregado')
            ->whereHas('medicamentoSolicitudSolicitud', function ($quericitoSolicitudes) use ($idMedicamento) {
                $quericitoSolicitudes->where('id_medicamento', $idMedicamento);
            })->where('id_unidad_recepciona', $idUnidadFarmacia)
            ->whereNot('fecha_entregado', null)
            ->get();

        //dd($solicitudesPorMedicamentoFarmacia);

        //RECETAS PARA LA EJECUCION SERÁ 4

        $recetaPorMedicamentoFarmacia = Receta::select('id', 'created_at')
            ->whereHas('recetaMedicamentoReceta', function ($quericitoRecetasEmitidas) use ($idMedicamento) {
                $quericitoRecetasEmitidas->where('id_medicamento', $idMedicamento);
            })
            ->whereIn('id_estado_validacion', [1, 2, 3, 4])
            ->get();

        //dd($recetaPorMedicamentoFarmacia);

        //RECETAS CADUCADAS PARA LA EJECUCION SERÁ 5

        $recetaCaducaPorMedicamentoFarmacia = Receta::select('id', 'updated_at')
            ->whereHas('recetaMedicamentoReceta', function ($quericitoRecetasEmitidas) use ($idMedicamento) {
                $quericitoRecetasEmitidas->where('id_medicamento', $idMedicamento);
            })
            ->where('id_estado_validacion', 4)
            ->get();

        //dd($recetaCaducaPorMedicamentoFarmacia);

        //RECETAS ANULADAS PARA LA EJECUCIÓN SERÁ 6

        $recetaAnuladaPorMedicamentoFarmacia = Receta::select('id', 'updated_at')
            ->whereHas('recetaMedicamentoReceta', function ($quericitoRecetasEmitidas) use ($idMedicamento) {
                $quericitoRecetasEmitidas->where('id_medicamento', $idMedicamento);
            })
            ->where('id_estado_validacion', 3)
            ->get();
        
        //dd($recetaAnuladaPorMedicamentoFarmacia);

        $historialMedicamentoAlmacen = collect();

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($cierresPorMedicamentoFarmacia->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 1, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->updated_at,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($reingresosPorMedicamentoFarmacia->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 2, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->updated_at,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($solicitudesPorMedicamentoFarmacia->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 3, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->fecha_entregado,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($recetaPorMedicamentoFarmacia->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 4, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->created_at,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($recetaCaducaPorMedicamentoFarmacia->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 5, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->updated_at,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($recetaAnuladaPorMedicamentoFarmacia->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 6, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->updated_at,
            ];
        }));

        $historialMedicamentoAlmacenOrdenado = $historialMedicamentoAlmacen->sortBy('fecha')->values()->all();

        dd($historialMedicamentoAlmacenOrdenado);

        $arrayParaPracticar = [];

        /*foreach ($historialMedicamentoAlmacenOrdenado as $key => $historial) {

            if ($historial['identificador'] === 1) {
                $this->funcionParaCierres($historial['id'], $idMedicamento, $idUnidadFarmacia);
            }

            if ($historial['identificador'] === 2) {
                $this->funcionParaIngresos($historial['id'], $idMedicamento, $idUnidadFarmacia);
            }

            if ($historial['identificador'] === 4) {
                $this->funcionParaSolicitudes($historial['id'], $idMedicamento, $idUnidadFarmacia);
            }
        }*/
    }

    public function regenerarMovimientosMedicamentoStockAlmacen($idMedicamento)
    {
        //Aqui armo el arraycito para que me extraiga todo lo que tiene un medicamento por grupo

        //Para Almacen de Medicamentos

        $idUnidadAlmacen = 89;

        //CIERRES DE GESTION PARA LA EJECUCION SERÁ 1 

        $cierresPorMedicamentoAlmacen = CierreGestion::select('id', 'updated_at')
            ->where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $idUnidadAlmacen)
            ->get();

        //INGRESOS PARA LA EJECUCION SERÁ 2

        $ingresosPorMedicamentoAlmacen = Ingreso::select('id', 'fecha_valida')
            ->whereHas('detalleIngresoIngreso', function ($quericito) use ($idMedicamento) {
                $quericito->where('id_medicamento', $idMedicamento);
            })
            ->whereNot('fecha_valida', null)
            ->get();


        //REINGRESOS PARA LA EJECUCION SERÁ 3

        $reingresosPorMedicamentoAlmacen = Reingreso::select('id', 'updated_at')
            ->whereHas('detallesMedicamento', function ($quericitoReingresos) use ($idMedicamento) {
                $quericitoReingresos->where('id_medicamento', $idMedicamento);
            })->where('id_unidad', $idUnidadAlmacen)
            ->get();

        //SOLICITUDES PARA LA EJECUCION SERÁ 4

        $solicitudesPorMedicamentoAlmacen = Solicitud::select('id', 'fecha_entregado')
            ->whereHas('medicamentoSolicitudSolicitud', function ($quericitoSolicitudes) use ($idMedicamento) {
                $quericitoSolicitudes->where('id_medicamento', $idMedicamento);
            })->where('id_unidad_recepciona', $idUnidadAlmacen)
            ->whereNot('fecha_entregado', null)
            ->get();

        $historialMedicamentoAlmacen = collect();

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($cierresPorMedicamentoAlmacen->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 1, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->updated_at,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($ingresosPorMedicamentoAlmacen->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 2, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->fecha_valida,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($reingresosPorMedicamentoAlmacen->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 3, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->updated_at,
            ];
        }));

        $historialMedicamentoAlmacen = $historialMedicamentoAlmacen->concat($solicitudesPorMedicamentoAlmacen->map(function ($registro) {
            return [
                'id' => $registro->id,
                'identificador' => 4, //Va a ser uno por el momento porque no me acuerdo cual era cual
                'fecha' => $registro->fecha_entregado,
            ];
        }));

        $historialMedicamentoAlmacenOrdenado = $historialMedicamentoAlmacen->sortBy('fecha')->values()->all();

        $arrayParaPracticar = [];

        foreach ($historialMedicamentoAlmacenOrdenado as $key => $historial) {

            if ($historial['identificador'] === 1) {
                $this->funcionParaCierres($historial['id'], $idMedicamento, $idUnidadAlmacen);
            }

            if ($historial['identificador'] === 2) {
                $this->funcionParaIngresos($historial['id'], $idMedicamento, $idUnidadAlmacen);
            }

            if ($historial['identificador'] === 4) {
                $this->funcionParaSolicitudes($historial['id'], $idMedicamento, $idUnidadAlmacen);
            }
        }
    }




    //FUNCIONES NECESARIAS PARA EFECTUAR EL CIERRE DE GESTION ------------------------------------------------------------------------------------------------------------

    public function funcionParaCierres($idCierre, $idMedicamento, $idUnidadAlmacen)
    {
        $cierre = CierreGestion::find($idCierre);
        if ($cierre->diferencia > 0) {
            //dd('la diferencia salio mayor que 0'); //SIGNIFICA QUE HAY MÁS EN SISTEMA QUE EN FÍSICO ---> Procede: SALIDA
            $this->obtencionDatosMovimientos($cierre->id, $idMedicamento, abs($cierre->diferencia), $cierre->fecha_vencimiento, 1, $idUnidadAlmacen, $cierre);
        } elseif ($cierre->diferencia === 0) {
            //dd('la diferencia salio 0'); //SIGNIFICA QUE NO EXISTE DIFERENCIA, ENTONCES NO SE HACER NADA MÁS QUE ACTUALIZAR EL ESTADO DE CIERRE_GESTION A TRUE ---> Solo se actualiza el último registro
            $this->obtencionDatosMovimientos($cierre->id, $idMedicamento, abs($cierre->diferencia), $cierre->fecha_vencimiento, 2, $idUnidadAlmacen, $cierre);
        } else {
            //dd('la diferencia salio menor que 0'); //SIGNIFICA QUE HAY MÁS FISICO QUE EN SISTEMA ---> Se realiza un: INGRESO
            $this->obtencionDatosMovimientos($cierre->id, $idMedicamento, abs($cierre->diferencia), $cierre->fecha_vencimiento, 3, $idUnidadAlmacen, $cierre);
        }
    }

    public function obtencionDatosMovimientos($idCierreGestion, $idMedicamento, $cantidad, $fechaVencimiento, $tipoDiferencia, $idUnidadAlmacen, $cierre)
    {
        //dd($idCierreGestion, $idMedicamento, $cantidad, $fechaVencimiento, $tipoDiferencia, $this->diferencia);
        /*
            DESCRIPCION DE TIPO DE DIFERENCIA
                SI ES "1" SIGNIFICA QUE ES SALIDA (HAY MAS EN SISTEMA)
                SI ES "2" SIGNIFICA QUE NO HAY DIFERENCIA
                SI ES "3" SIGNIFICA QUE ES INGRESO (HAY MENOS EN EL SISTEMA)
        */

        $datosCierre = CierreGestion::find($idCierreGestion);

        if ($tipoDiferencia === 1) {
            $datosNecesariosParaMovimientosSalida = [
                'id_corresponde' => $idCierreGestion,
                'id_unidad' => $idUnidadAlmacen,
                'nro_documento' => 'CIERRE-GESTION-ALMACEN-FARMACIA' . Carbon::now()->format('Y'),
                'updated_at' => $cierre->created_at,
                'id_user_created' => $cierre->id_user_created,
            ];

            $this->procesarCierreGestionSalida($idMedicamento, $cantidad, $datosNecesariosParaMovimientosSalida, $fechaVencimiento);
        } elseif ($tipoDiferencia === 2) {

            $this->procesarCierreGestionSinDiferencia($idMedicamento, $fechaVencimiento, $cantidad, $idCierreGestion, $idUnidadAlmacen, $datosCierre);
        } elseif ($tipoDiferencia === 3) {

            $this->procesarCierreGestionIngreso($idMedicamento, $cantidad, $fechaVencimiento, $idCierreGestion, $idUnidadAlmacen, $datosCierre);
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
                'created_at' => $datosNecesarios['updated_at'],
                'id_user_created' => $datosNecesarios['id_user_created'],
            ];

            $lote->stock_actualizado = $disponible - $cantidadARestar;
            $lote->cierre_gestion = true;
            $lote->fecha_vencimiento = $fechaVencimiento;
            $lote->save();

            $cantidad -= $cantidadARestar;

            $this->registrarMovimientoSalida($datosParaMovimientos);
        }
    }

    public function registrarMovimientoSalida($datosNecesarios)
    {
        //PARA LA TABLA DE MOVIMIENTOS 
        $movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->orderBy('id', 'desc')
            ->first();

        $nuevoSaldo = $movimientoAnterior->saldo - $datosNecesarios['cantidad'];

        $nuevoMovimiento = Movimiento::create([
            'id_corresponde' => $datosNecesarios['id_corresponde'],
            'id_identificador_tabla' => 4,
            'dato_solicitante' => $datosNecesarios['dato_solicitante'],
            'nro_documento' => $datosNecesarios['nro_documento'],
            'fecha' => $datosNecesarios['created_at'],
            'dato_interesado' => $datosNecesarios['dato_interesado'],
            'cantidad_ingreso' => 0,
            'cantidad_salida' => $datosNecesarios['cantidad'],
            'saldo' => $nuevoSaldo,
            'id_estado_validacion' => 2,
            'id_medicamento' => $datosNecesarios['id_medicamento'],
            'id_unidad' => $datosNecesarios['id_unidad'],
            'nro_lote' => $datosNecesarios['nro_lote'],
            'id_user_created' => $datosNecesarios['id_user_created'],
            'precio_unitario_peps' => $datosNecesarios['precio_unitario_peps'],
            'fecha_vencimiento' => $datosNecesarios['fecha_vencimiento'],
            'cierre_gestion' => true,
            'created_at' => $datosNecesarios['created_at'],
            'updated_at' => $datosNecesarios['created_at'],
        ]);

        /*$nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
            'id_cierre_gestion' => $this->idCierreGestionRegistrado,
            'id_unidad' => $this->idAlmacenFarmacia,
            'id_medicamento_stock' => null,
            'id_movimiento' => $nuevoMovimiento->id,
            'cantidad' => $datosNecesarios['cantidad'],
            'tipo_movimiento' => 'SALIDA',
        ]);*/

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }

    public function procesarCierreGestionIngreso($idMedicamento, $cantidad, $fechaVencimiento, $idCierreGestion, $idUnidadAlmacen, $datosCierre)
    {
        $maxNroLote = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $idUnidadAlmacen)
            ->max('nro_lote');

        if ($maxNroLote) {
            $ultimoPrecioUnitario = MedicamentoStock::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $idUnidadAlmacen)
                ->where('nro_lote', $maxNroLote)
                ->first();

            $nroLotePorMedicamento = $maxNroLote ? $maxNroLote + 1 : 1;

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => $ultimoPrecioUnitario->precio_unitario,
                'stock_actualizado' => $cantidad,
                'nro_lote' => $nroLotePorMedicamento,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $idUnidadAlmacen,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => $datosCierre->id_user_created,
                'id_user_updated' => $datosCierre->id_user_updated,
                'created_at' => $datosCierre->updated_at,
                'updated_at' => $datosCierre->updated_at,
            ]);

            // FIN DE LA INSERCION EN LA TABLA MEDICAMENTOS STOCK -----------------------------------------------------

            //PARA LA TABLA MOVIMIENTO

            $movimientoPrevio = Movimiento::where('id_medicamento', $idMedicamento)
                ->where('id_unidad', $idUnidadAlmacen)
                //->where('nro_lote', $this->nro_lote)
                ->orderBy('id', 'desc')
                ->first();

            $saldo = 0;

            if ($movimientoPrevio) {
                //DADO QUE ES UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                $saldo = $cantidad + $movimientoPrevio->saldo;
            } else {
                $saldo = $cantidad;
            }

            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $idCierreGestion,
                'id_identificador_tabla' => 4,
                'dato_solicitante' => 'ALMACEN DE FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => $datosCierre->created_at,
                'dato_interesado' => 'CIERRE DE GESTION - 2025',
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $saldo,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $idUnidadAlmacen,
                'nro_lote' => $nroLotePorMedicamento,
                'precio_unitario_peps' => $ultimoPrecioUnitario->precio_unitario,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => $datosCierre->id_user_created,
                'cierre_gestion' => true,
                'created_at' => $datosCierre->updated_at,
                'updated_at' => $datosCierre->updated_at,
            ]);
        } else {

            //PARA MEDICAMENTOS STOCK EN CIERRE DE GESTION (LOGICA FUNCIONAL UNICAMENTE PARA LA PUESTA A PRODUCCION DEL SIGHOV2 ETAPA 2)

            $nuevoMedicamentoStock = MedicamentoStock::create([
                'cantidad_inicial' => $cantidad,
                'precio_unitario' => 0,
                'stock_actualizado' => $cantidad,
                'nro_lote' => 1,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $idUnidadAlmacen,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => $datosCierre->id_user_created,
            ]);

            //FIN DE MEDICAMENTOS STOCK

            //PARA MOVIMIENTOS

            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $idCierreGestion,
                'id_identificador_tabla' => 4,
                'dato_solicitante' => 'ALMACEN DE FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => $datosCierre->created_at,
                'dato_interesado' => 'CIERRE DE GESTION - 2025',
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $cantidad,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $idUnidadAlmacen,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => $datosCierre->id_user_created,
                'cierre_gestion' => true,
                'created_at' => $datosCierre->updated_at,
                'updated_at' => $datosCierre->updated_at,
            ]);

            //FIN DE MOVIMIENTOS
        }
    }

    public function procesarCierreGestionSinDiferencia($idMedicamento, $fechaVencimiento, $cantidad, $idCierreGestion, $idUnidadAlmacen, $datosCierre)
    {
        $medicamentoStock = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $idUnidadAlmacen)
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
                'id_unidad' => $idUnidadAlmacen,
                'fecha_vencimiento' => $fechaVencimiento,
                'cierre_gestion' => true,
                'id_user_created' => $datosCierre->id_user_created,
            ]);
        }

        $movimiento = Movimiento::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $idUnidadAlmacen)
            ->orderBy('id', 'desc')
            ->first();

        if ($movimiento) {
            $movimiento->cierre_gestion = true;
            $movimiento->fecha_vencimiento = $fechaVencimiento;
            $movimiento->observacion = 'CIERRE DE GESTIÓN CORRESPONDIENTE A LA GESTIÓN 2025';
            $movimiento->save();
        } else {
            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $idCierreGestion,
                'id_identificador_tabla' => 4,
                'dato_solicitante' => 'ALMACEN DE FARMACIA',
                'nro_documento' => $idCierreGestion,
                'fecha' => $datosCierre->created_at,
                'dato_interesado' => 'CIERRE DE GESTION - 2025',
                'cantidad_ingreso' => $cantidad,
                'cantidad_salida' => 0,
                'saldo' => $cantidad,
                'estado_validacion' => 'ENTREGADO', //POR DEFECTO, PORQUE YA SE COMPRO DE LA EMPRESA, EN OTROS CASOS ES DIFERENTE
                'id_estado_validacion' => 5,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $idUnidadAlmacen,
                'nro_lote' => 1,
                'precio_unitario_peps' => 0,
                'fecha_vencimiento' => $fechaVencimiento,
                'id_user_created' => $datosCierre->id_user_created,
                'cierre_gestion' => true,
                'created_at' => $datosCierre->updated_at,
                'updated_at' => $datosCierre->updated_at,
            ]);
        }
    }

    //FIN FUNCIONES NECESARIAS PARA EFECTUAR EL CIERRE DE GESTION---------------------------------------------------------------------------------------------------------


    //FUNCIONES NECESARIAS PARA EFECTUAR EL INGRESO ------------------------------------------------------------------------------------------------------------

    public function funcionParaIngresos($idIngreso, $idMedicamento, $idUnidadAlmacen)
    {
        $ingresoAValidar = Ingreso::find($idIngreso);
        $ingresos = DetalleIngreso::where('id_ingreso', $idIngreso)->where('id_medicamento', $idMedicamento)->get();

        if (count($ingresos) > 0) {
            foreach ($ingresos as $key => $ingreso) {
                $this->registrarMovimientosMedStock($ingreso, $idUnidadAlmacen, $idIngreso, $ingresoAValidar);
            }
        }
    }

    public function registrarMovimientosMedStock($detalleIngresoAValidar, $idUnidadAlmacen, $idIngreso, $ingresoAValidar)
    {
        //INSERCION A LA TABLA MEDICAMENTOS STOCK

        $maxNroLote = MedicamentoStock::where('id_medicamento', $detalleIngresoAValidar->id_medicamento)
            ->where('id_unidad', $idUnidadAlmacen)
            ->max('nro_lote');

        $nroLotePorMedicamento = $maxNroLote ? $maxNroLote + 1 : 1;

        $nuevoMedicamentoStock = MedicamentoStock::create([
            'cantidad_inicial' => $detalleIngresoAValidar->cantidad,
            'precio_unitario' => $detalleIngresoAValidar->precio_unitario,
            'stock_actualizado' => $detalleIngresoAValidar->cantidad,
            'nro_lote' => $nroLotePorMedicamento,
            'id_medicamento' => $detalleIngresoAValidar->id_medicamento,
            'id_unidad' => $idUnidadAlmacen,
            'fecha_vencimiento' => $detalleIngresoAValidar->fecha_vencimiento,
            'ajuste_contable' => false,
            'id_user_created' => $ingresoAValidar->id_user_valida,
            'id_ingreso' => $idIngreso,
            'created_at' => $ingresoAValidar->fecha_valida,
            'updated_at' => $ingresoAValidar->fecha_valida,
        ]);

        // FIN DE LA INSERCION EN LA TABLA MEDICAMENTOS STOCK -----------------------------------------------------

        //PARA LA TABLA MOVIMIENTO

        $movimientoPrevio = Movimiento::where('id_medicamento', $detalleIngresoAValidar->id_medicamento)
            ->where('id_unidad', $idUnidadAlmacen)
            //->where('nro_lote', $this->nro_lote)
            ->orderBy('id', 'desc')
            ->first();

        $saldo = 0;

        if ($movimientoPrevio) {
            //DADO QUE ES UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
            $saldo = $detalleIngresoAValidar->cantidad + $movimientoPrevio->saldo;
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
            'id_unidad' => $idUnidadAlmacen,
            'nro_lote' => $nroLotePorMedicamento,
            'precio_unitario_peps' => $detalleIngresoAValidar->precio_unitario,
            'fecha_vencimiento' => $detalleIngresoAValidar->fecha_vencimiento,
            'id_user_created' => $ingresoAValidar->id_user_valida,
            'created_at' => $ingresoAValidar->fecha_valida,
            'updated_at' => $ingresoAValidar->fecha_valida,
        ]);

        //FIN DE LA TABLA MOVIMIENTOS ----------------------------------------------------------------------
    }

    //FIN FUNCIONES NECESARIAS PARA EFECTUAR EL INGRESO---------------------------------------------------------------------------------------------------------






    //FUNCIONES NECESARIAS PARA EFECTUAR LAS SOLICITUDES ------------------------------------------------------------------------------------------------------------

    public function obtenerNombreUnidad($idUnidad)
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidad)['nombre'];
    }

    public function funcionParaSolicitudes($idSolicitud, $idMedicamento, $idUnidadAlmacen)
    {
        $medicamentosSolicitud = MedicamentoSolicitud::where('id_solicitud', $idSolicitud)->where('id_medicamento', $idMedicamento)->get();
        $solicitudUnidad = Solicitud::find($idSolicitud);
        $unidadSolicitante = $this->obtenerNombreUnidad($solicitudUnidad->id_unidad_solicita);

        foreach ($medicamentosSolicitud as $key => $med) {
            //dd($med);

            if ($med->cantidad_solicitada === null) {

                $arrayDatosNecesariosParaMedicamentoStock = [
                    'id_almacen' => $idUnidadAlmacen,
                    'id_medicamento' => $med->id_medicamento,
                    'cantidad_entregada' => $med->cantidad_entregada,
                    'id_farmacia' => 76,
                ];

                $arrayDatosNecesariosParaMovimiento = [
                    'id_corresponde' => $idSolicitud,
                    'dato_solicitante' => 'FARMACIA',
                    'nro_documento' => Solicitud::select('cod_solicitud')->where('id', $idSolicitud)->first()->cod_solicitud,
                    'datos_interesado' => 'ALMACEN DE FARMACIA',
                    'id_medicamento' => $med->id_medicamento
                ];

                $this->registrarMedicamentoStockSolicitudDeFarmaciaAAlmacen($arrayDatosNecesariosParaMedicamentoStock, $arrayDatosNecesariosParaMovimiento, $solicitudUnidad);
            } else {

                $cantidad = $med->cantidad_entregada;
                $this->procesarLotesEntrega($idMedicamento, $cantidad, $idUnidadAlmacen, $solicitudUnidad, $unidadSolicitante);
            }
        }
    }

    private function procesarLotesEntrega(int $idMedicamento, int $cantidad, $idUnidadAlmacen, $solicitudUnidad, $unidadSolicitante)
    {
        $lotes = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $idUnidadAlmacen)
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
                'id_corresponde' => $solicitudUnidad->id,
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $solicitudUnidad->id_unidad_recepciona,
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $unidadSolicitante,
                'nro_documento' => $solicitudUnidad->cod_solicitud,
                'dato_interesado' => 'ALMACEN DE FARMACIA',
                'nro_lote' => $lote->nro_lote,
                'precio_unitario_peps' => $lote->precio_unitario,
                'fecha_vencimiento' => $lote->fecha_vencimiento,
            ];

            $lote->stock_actualizado = $disponible - $cantidadARestar;
            $lote->save();

            $cantidad -= $cantidadARestar;

            $this->registrarMovimientoSolicitudUnidadAFarmacia($datosParaMovimientos, $solicitudUnidad);
        }
    }

    public function registrarMovimientoSolicitudUnidadAFarmacia($datosNecesarios, $solicitudUnidad)
    {
        //PARA LA TABLA DE MOVIMIENTOS 
        $movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->orderBy('id', 'desc')
            ->first();
        $nuevoSaldo = $movimientoAnterior->saldo - $datosNecesarios['cantidad'];


        $nuevoMovimiento = Movimiento::create([
            'id_corresponde' => $datosNecesarios['id_corresponde'],
            'id_identificador_tabla' => 2,
            'dato_solicitante' => $datosNecesarios['dato_solicitante'],
            'nro_documento' => $datosNecesarios['nro_documento'],
            'fecha' => $solicitudUnidad->fecha_entregado,
            'dato_interesado' => $datosNecesarios['dato_interesado'],
            'cantidad_ingreso' => 0,
            'cantidad_salida' => $datosNecesarios['cantidad'],
            'saldo' => $nuevoSaldo,
            'id_estado_validacion' => 6,
            'id_medicamento' => $datosNecesarios['id_medicamento'],
            'id_unidad' => $datosNecesarios['id_unidad'],
            'nro_lote' => $datosNecesarios['nro_lote'],
            'id_user_created' => $solicitudUnidad->id_usuario_entrega,
            'precio_unitario_peps' => $datosNecesarios['precio_unitario_peps'],
            'fecha_vencimiento' => $datosNecesarios['fecha_vencimiento'],
            'created_at' => $solicitudUnidad->fecha_entregado,
            'updated_at' => $solicitudUnidad->fecha_entregado,
        ]);

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }

    public function registrarMedicamentoStockSolicitudDeFarmaciaAAlmacen($arrayDatos, $arrayDatosMovimiento, $solicitudUnidad)
    {
        //dd($arrayDatos, $arrayDatosMovimiento);
        $medStockAlmacenPorLotes = MedicamentoStock::where('id_unidad', $arrayDatos['id_almacen'])
            ->where('id_medicamento', $arrayDatos['id_medicamento'])
            ->where('stock_actualizado', '>', 0)
            //->where('fecha_vencimiento', '>', Carbon::now())
            ->orderBy('nro_lote', 'asc')
            ->get();

        if (count($medStockAlmacenPorLotes) > 0) {
            //PARA REGISTRAR EL STOCK EN MEDICAMENTO STOCK PERTENECIENTE A ALMACEN
            $cantidadARestar = $arrayDatos['cantidad_entregada'];
            foreach ($medStockAlmacenPorLotes as $loteAlmacen) {
                if ($cantidadARestar > 0) {
                    if ($loteAlmacen->stock_actualizado > 0) {
                        if ($cantidadARestar >= $loteAlmacen->stock_actualizado) {
                            $this->registrarMovimientoSolicitudDeFarmaciaAAlmacen(
                                $arrayDatosMovimiento,
                                false,
                                $loteAlmacen->stock_actualizado,
                                $arrayDatos['cantidad_entregada'],
                                $arrayDatos['id_almacen'],
                                $loteAlmacen->nro_lote,
                                $loteAlmacen->precio_unitario,
                                $loteAlmacen->fecha_vencimiento,
                                $solicitudUnidad
                            );
                            $cantidadARestar = $cantidadARestar - $loteAlmacen->stock_actualizado;
                            $loteAlmacen->stock_actualizado = 0;
                            $loteAlmacen->id_user_updated = auth()->user()->id;
                            $loteAlmacen->save();
                        } else {
                            $this->registrarMovimientoSolicitudDeFarmaciaAAlmacen(
                                $arrayDatosMovimiento,
                                false,
                                $cantidadARestar,
                                $arrayDatos['cantidad_entregada'],
                                $arrayDatos['id_almacen'],
                                $loteAlmacen->nro_lote,
                                $loteAlmacen->precio_unitario,
                                $loteAlmacen->fecha_vencimiento,
                                $solicitudUnidad
                            );
                            $loteAlmacen->stock_actualizado = $loteAlmacen->stock_actualizado - $cantidadARestar;
                            $loteAlmacen->id_user_updated = auth()->user()->id;
                            $loteAlmacen->save();
                            $cantidadARestar = 0;
                        }
                    }
                }
            }

            //PARA REGISTRAR EL NUEVO INGRESO EN EL "ALMACEN IMAGINARIO DE FARMACIA" POR ENDE 
            //TIENE QUE TENER NUEVO NUMERO DE LOTE POR MEDICAMENTO TAMBIEN, LO MISMO SE REPLICARÁ
            //EM ALMACEN DE FARMACIA
            /*$ultimaFechaVencimiento = $medStockAlmacenPorLotes->last()->fecha_vencimiento;
            $ultimoPrecioUnitario = $medStockAlmacenPorLotes->last()->precio_unitario;
            $nroLoteFarmaciaUltimo = MedicamentoStock::where('id_medicamento', $arrayDatos['id_medicamento'])
                ->where('id_unidad', $arrayDatos['id_farmacia'])
                ->orderBy('nro_lote', 'desc')
                ->first();

            //dd($arrayDatos);

            $nroLoteSolicitudFarmaciaPorMedicamento = $nroLoteFarmaciaUltimo ? $nroLoteFarmaciaUltimo->nro_lote + 1 : 1;

            $nuevoStockFarmacia = MedicamentoStock::create([
                'cantidad_inicial' => $arrayDatos['cantidad_entregada'],
                'precio_unitario' => $ultimoPrecioUnitario,
                'stock_actualizado' => $arrayDatos['cantidad_entregada'],
                'nro_lote' => $nroLoteSolicitudFarmaciaPorMedicamento,
                'id_medicamento' => $arrayDatos['id_medicamento'],
                'id_user_created' => $solicitudUnidad->id_usuario_entrega,
                'id_unidad' => $arrayDatos['id_farmacia'],
                'ajuste_contable' => false,
                'fecha_vencimiento' => $ultimaFechaVencimiento,
                'created_at' => $solicitudUnidad->fecha_entregado,
                'updated_at' => $solicitudUnidad->fecha_entregado,
            ]);

            $this->registrarMovimientoSolicitudDeFarmaciaAAlmacen(
                $arrayDatosMovimiento,
                true,
                $cantidadARestar,
                $arrayDatos['cantidad_entregada'],
                $arrayDatos['id_farmacia'],
                $nroLoteSolicitudFarmaciaPorMedicamento,
                $ultimoPrecioUnitario,
                $ultimaFechaVencimiento,
                $solicitudUnidad
            );
            //PARA REGISTRAR UN NUEVO "INGRESO" EN EL "ALMACEN" DE FARMACIA (CONTAMOS CON QUE 
            // FARMACIA TIENE UNA ESPECIE DE ALMACEN IMAGINARIO. POR ENDE, LA CANTIDAD )*/
        }
    }

    public function registrarMovimientoSolicitudDeFarmaciaAAlmacen(
        $arrayDatosMovimiento,
        $ingresoSwitch,
        $cantidadARestar,
        $cantidadEntregada,
        $idUnidad,
        $nroLote,
        $peps,
        $fechaVencimiento,
        $solicitudUnidad
    ) //ingreso = true, salida = false
    {
        $movimientoPrevioPorUnidadYMedicamento = Movimiento::where('id_unidad', $idUnidad)
            ->where('id_medicamento', $arrayDatosMovimiento['id_medicamento'])
            ->orderBy('id', 'desc')
            ->first();

        $cantidadIngreso = 0;
        $cantidadSalida = 0;
        $saldo = 0;
        $idEstadoValidacion = 5;

        if ($ingresoSwitch) {
            if ($movimientoPrevioPorUnidadYMedicamento) {
                $nroLote = $movimientoPrevioPorUnidadYMedicamento->nro_lote + 1;
                $cantidadIngreso = $cantidadEntregada;
                $saldo = $cantidadEntregada + $movimientoPrevioPorUnidadYMedicamento->saldo;
                $idEstadoValidacion = 6;
            } else {
                $nroLote = 1;
                $cantidadIngreso = $cantidadEntregada;
                $saldo = $cantidadEntregada;
                $idEstadoValidacion = 6;
            }
        } else {
            if ($movimientoPrevioPorUnidadYMedicamento) {
                $cantidadSalida = $cantidadARestar;
                $saldo = $movimientoPrevioPorUnidadYMedicamento->saldo - $cantidadARestar;
            }
        }

        $nuevoMovimientoPorLote = [
            'id_corresponde' => $arrayDatosMovimiento['id_corresponde'],
            'id_identificador_tabla' => 2,
            'dato_solicitante' => $arrayDatosMovimiento['dato_solicitante'],
            'nro_documento' => $arrayDatosMovimiento['nro_documento'],
            'fecha' => $solicitudUnidad->fecha_entregado,
            'dato_interesado' => $arrayDatosMovimiento['datos_interesado'],
            'cantidad_ingreso' => $cantidadIngreso,
            'cantidad_salida' => $cantidadSalida,
            'saldo' => $saldo,
            'id_estado_validacion' => $idEstadoValidacion,
            'id_medicamento' => $arrayDatosMovimiento['id_medicamento'],
            'id_user_created' => $solicitudUnidad->id_usuario_entrega,
            'id_unidad' => $idUnidad,
            'cierre_gestion' => false,
            'nro_lote' => $nroLote,
            'precio_unitario_peps' => $peps,
            'fecha_vencimiento' => $fechaVencimiento,
            'created_at' => $solicitudUnidad->fecha_entregado,
            'updated_at' => $solicitudUnidad->fecha_entregado
        ];

        $nuevoMovimiento = Movimiento::create($nuevoMovimientoPorLote);
    }

    //FIN FUNCIONES NECESARIAS PARA EFECTUAR LAS SOLICITUDES---------------------------------------------------------------------------------------------------------

}
