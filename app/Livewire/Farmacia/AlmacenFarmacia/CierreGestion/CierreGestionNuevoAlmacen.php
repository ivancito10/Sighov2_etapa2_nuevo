<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\CierreGestion;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Movimiento;
use App\Services\ObtenerDatosPersonalSsu;
use App\Models\Administracion\Persona;
use App\Services\ListaUnidades;
use Illuminate\Support\Facades\DB;
use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\ControlCierreGestion;
use App\Models\Farmacia\MedicamentoStock;
use Carbon\Carbon;


class CierreGestionNuevoAlmacen extends Component
{

    public $idMed, $cantidad_fisica, $cantidad_bajas, $diferencia, $fecha_vencimiento;
    public $cod_medicamento, $medicamento, $forma_farmaceutica, $concentracion,
        $nombre_completo, $carnet, $unidadSolicitante, $cargoSolicitante,
        $stock_actual, $hoy, $fecha_vencimiento_baja, $mostrarDiferencia;
    public $idCierreGestionRegistrado;
    public $idAlmacenFarmacia;

    public $fechaDefectoMomentaneo;
    public $opcionesBajaMedicamento;


    public function render()
    {
        return view('livewire.farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-nuevo-almacen');
    }

    public function mount()
    {
        $this->idAlmacenFarmacia = 89;
        $this->opcionesBajaMedicamento = false;
        $this->fechaDefectoMomentaneo = Carbon::parse('2027-12-31')->format('Y-m-d');
    }

    #[On('formulario_cierre_gestion')]
    public function formulario_cierre_gestion($data)
    {
        // dd($data);
        $this->idMed = $data;
        $this->dispatch('cerrar_listar_medicamento');
        $this->dispatch('registrar_cierre');
        // $this->reset('cantidad_fisica', 'diferencia', 'fecha_vencimiento');
        $this->mostrarMedicamento($data);
        $this->reseteoo();
        //FECHA POR DEFECTO SOLO PARA EL "INVENTARIO" REALIZADO EL 14 03 2026
        $this->fecha_vencimiento = $this->fechaDefectoMomentaneo;
        //  $this->skipRender();
    }

    public function mostrarMedicamento($id)
    {
        if ($id) {
            $medicamento = Medicamento::find($id);
            $this->cod_medicamento = $medicamento->codigo_medicamento;
            $this->medicamento = $medicamento->nombre;
            $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
            $this->concentracion = $medicamento->concentracion;
            $id_persona = auth()->user()->id_persona;

            /*$stock = Movimiento::where('id_medicamento', $id)
                ->where('id_unidad', $this->idAlmacenFarmacia)
                ->orderBy('id', 'desc')
                ->first();*/

            $stockAlmacen = MedicamentoStock::where('id_medicamento', $id)
                ->where('id_unidad', $this->idAlmacenFarmacia)
                ->sum('stock_actualizado');

            $this->stock_actual = $stockAlmacen ?? 0;
            //dd($this->stock_actual);
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
            return $medicamento;
        }
    }

    public function procesarCantidad()
    {

        if ($this->cantidad_fisica != null) {
            $this->diferencia = $this->stock_actual - $this->cantidad_fisica;
            $this->mostrarDiferencia = abs($this->diferencia);
            // $this->skipRender();
        } else {
            $this->cantidad_fisica = 0;
            $this->diferencia =  $this->stock_actual - $this->cantidad_fisica;
            $this->mostrarDiferencia = abs($this->diferencia);
            //  $this->skipRender();
        }
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    public function rules()
    {
        $rules = [
            'cantidad_fisica' => 'required|gte:0',
            'mostrarDiferencia' => 'required',
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

    #[On('guardar_cierre_almacen')]
    public function guardar_cierre_almacen()
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
        try {

            $cierre_ant = CierreGestion::where('id_medicamento', $this->idMed)
                ->where('id_unidad', $this->idAlmacenFarmacia) // Si necesitas validar por unidad
                ->orderBy('fecha_registro', 'desc') // Ordena por la fecha más reciente
                ->first();
            if ($cierre_ant) {
                $fecha_validate = Carbon::parse($cierre_ant->fecha_registro);
                $diasDiferencia = $fecha_validate->diffInDays(Carbon::now());

                //if ($diasDiferencia > 3) {

                $cierre = CierreGestion::create([
                    'cantidad_fisica' => $this->cantidad_fisica,
                    'cantidad_bajas' => $this->cantidad_bajas,
                    'cantidad_sistema' => $this->stock_actual,
                    'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                    'diferencia' => $this->diferencia,
                    'id_unidad' => $this->idAlmacenFarmacia,
                    'id_medicamento' => $this->idMed,
                    'fecha_vencimiento' => $this->fecha_vencimiento,
                    'fecha_vencimiento_baja' => $this->fecha_vencimiento_baja,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($cierre) {
                    $this->idCierreGestionRegistrado = $cierre->id;
                    if ($this->diferencia > 0) {
                        //dd('la diferencia salio mayor que 0'); //SIGNIFICA QUE HAY MÁS EN SISTEMA QUE EN FÍSICO ---> Procede: SALIDA
                        $this->obtencionDatosMovimientos($cierre->id, $this->idMed, abs($this->diferencia), $this->fecha_vencimiento, 1);
                    } elseif ($this->diferencia === 0) {
                        //dd('la diferencia salio 0'); //SIGNIFICA QUE NO EXISTE DIFERENCIA, ENTONCES NO SE HACER NADA MÁS QUE ACTUALIZAR EL ESTADO DE CIERRE_GESTION A TRUE ---> Solo se actualiza el último registro
                        $this->obtencionDatosMovimientos($cierre->id, $this->idMed, abs($this->diferencia), $this->fecha_vencimiento, 2);
                    } else {
                        //dd('la diferencia salio menor que 0'); //SIGNIFICA QUE HAY MÁS FISICO QUE EN SISTEMA ---> Se realiza un: INGRESO
                        $this->obtencionDatosMovimientos($cierre->id, $this->idMed, abs($this->diferencia), $this->fecha_vencimiento, 3);
                    }

                    $this->dispatch('cierre_gestion_almacen_registrado');
                    $this->dispatch('pg:eventRefresh-CierreAlmacenFarmaciaTable');
                    $this->dispatch('pg:eventRefresh-MiedicamentosTableFarmacia');
                    $this->reseteoo();
                }
                /*} else {
                    $this->dispatch('hay_registro_almacen');
                }*/
            } else {
                $cierre = CierreGestion::create([
                    'cantidad_fisica' => $this->cantidad_fisica,
                    'cantidad_bajas' => $this->cantidad_bajas,
                    'cantidad_sistema' => $this->stock_actual,
                    'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                    'diferencia' => $this->diferencia,
                    'id_unidad' => $this->idAlmacenFarmacia,
                    'id_medicamento' => $this->idMed,
                    'fecha_vencimiento' => $this->fecha_vencimiento,
                    'fecha_vencimiento_baja' => $this->fecha_vencimiento_baja,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($cierre) {
                    $this->idCierreGestionRegistrado = $cierre->id;
                    if ($this->diferencia > 0) {
                        //dd('la diferencia salio mayor que 0'); //SIGNIFICA QUE HAY MÁS EN SISTEMA QUE EN FÍSICO ---> Procede: SALIDA
                        $this->obtencionDatosMovimientos($cierre->id, $this->idMed, abs($this->diferencia), $this->fecha_vencimiento, 1);
                    } elseif ($this->diferencia === 0) {
                        //dd('la diferencia salio 0'); //SIGNIFICA QUE NO EXISTE DIFERENCIA, ENTONCES NO SE HACER NADA MÁS QUE ACTUALIZAR EL ESTADO DE CIERRE_GESTION A TRUE ---> Solo se actualiza el último registro
                        $this->obtencionDatosMovimientos($cierre->id, $this->idMed, abs($this->diferencia), $this->fecha_vencimiento, 2);
                    } else {
                        //dd('la diferencia salio menor que 0'); //SIGNIFICA QUE HAY MÁS FISICO QUE EN SISTEMA ---> Se realiza un: INGRESO
                        $this->obtencionDatosMovimientos($cierre->id, $this->idMed, abs($this->diferencia), $this->fecha_vencimiento, 3);
                    }

                    $this->dispatch('cierre_gestion_almacen_registrado');
                    $this->dispatch('pg:eventRefresh-CierreAlmacenFarmaciaTable');
                    $this->dispatch('pg:eventRefresh-MiedicamentosTableFarmacia');
                    $this->reseteoo();
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('cierre_error');
        }
    }

    public function reseteoo()
    {
        $this->reset('cantidad_fisica', 'cantidad_bajas', 'diferencia', 'mostrarDiferencia', 'fecha_vencimiento', 'fecha_vencimiento_baja');
    }

    public function camcelarCierre()
    {
        $this->dispatch('cierre_cancelar');
        return redirect()->route('farmacia.almacen-farmacia.cierre-gestion.index');
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
                'id_unidad' => $this->idAlmacenFarmacia,
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
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
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
            ->orderBy('fecha', 'desc')
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
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
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
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
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
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
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
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
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
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
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
            $movimiento->observacion = 'CIERRE DE GESTIÓN CORRESPONDIENTE A LA GESTIÓN ' . Carbon::now('Y');
            $movimiento->save();

            $nuevoControlCierreGestionMovimiento = ControlCierreGestion::create([
                'id_cierre_gestion' => $this->idCierreGestionRegistrado,
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
}
