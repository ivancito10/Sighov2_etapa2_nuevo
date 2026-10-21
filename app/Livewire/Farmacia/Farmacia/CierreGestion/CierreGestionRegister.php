<?php

namespace App\Livewire\Farmacia\Farmacia\CierreGestion;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Farmacia\Medicamento;
use App\Services\ObtenerDatosPersonalSsu;
use App\Models\Administracion\Persona;
use App\Models\Farmacia\MedicamentoStock;

use App\Services\ListaUnidades;
use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\Movimiento;
use Carbon\Carbon;

class CierreGestionRegister extends Component
{

    public $idMed;
    public $cod_medicamento, $medicamento, $forma_farmaceutica, $concentracion,
        $unidadSolicitante, $cargoSolicitante, $nombre_completo, $carnet,
        $stock_actual, $cantidad_fisica, $diferencia, $fecha_vencimiento, $id_cierre, $ingreso, $salida, $saldo,
        $precio_anterior, $cantidad_bajas;
    public $hoy;

    public function render()
    {
        return view('livewire.farmacia.farmacia.cierre-gestion.cierre-gestion-register');
    }

    /*    public function mount()
    {
        $this->hoy = now()->toDateString(); // Obtiene la fecha actual en formato 'YYYY-MM-DD'
    } */

    public function lista_medicamento_farmacia()
    {
        $this->dispatch('listado_modal_farmacia');
    }

    /*   #[On('formulario_cierre_gestion')]
    public function formulario_cierre_gestion($data)
    {
        //dd($data);
        $this->idMed = $data;
        //  dd($this->idMed);
        $this->mostrarMedicamento($data);

        $this->dispatch('registrar_cierre_farmacia');
        $this->skipRender();
    } */
    /*   public function resetearVariables()
    {
        $this->reset(
            'cod_medicamento',
            'medicamento',
            'forma_farmaceutica',
            'concentracion',
            'stock_actual',
            'cantidad_fisica',
            'diferencia',
            'fecha_vencimiento'
        );
    }
    public function mostrarMedicamento($id)
    {
        if ($id) {
            $medicamento = Medicamento::find($id);
            // dd($medicamento);
            $this->cod_medicamento = $medicamento->codigo_medicamento;
            $this->medicamento = $medicamento->nombre;
            $this->forma_farmaceutica = $medicamento->forma_farmaceutica;
            $this->concentracion = $medicamento->concentracion;

            $stock = Movimiento::where('id_medicamento', $id)
                ->where('id_unidad', $this->obtenerIdFarmacia())
                ->orderBy('id', 'desc')
                ->first();

            $this->stock_actual = $stock->saldo ?? 0;

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

    public function procesarCantidad()
    {
        if ($this->cantidad_fisica != null) {
            $this->diferencia =  $this->stock_actual - $this->cantidad_fisica;
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
        $this->skipRender();
    }


    #[On('funcion_guardar_cierre_gestion')]
    public function funcion_guardar_cierre_gestion()
    {
        try {
            if ($this->cantidad_fisica != null && $this->fecha_vencimiento != null) {

                $cierre_ant = CierreGestion::where('id_medicamento', $this->idMed)
                    ->where('id_unidad', $this->obtenerIdFarmacia())
                    ->orderBy('fecha_registro', 'desc')
                    ->first();

                if ($cierre_ant) {
                    $fecha_validate = Carbon::parse($cierre_ant->fecha_registro);
                    $diasDiferencia = $fecha_validate->diffInDays(Carbon::now());

                    if ($diasDiferencia > 3) {
                        $cierre = CierreGestion::create([
                            'cantidad_fisica' => $this->cantidad_fisica,
                            'cantidad_sistema' => $this->stock_actual,
                            'cantidad_bajas'  => $this->cantidad_bajas,
                            'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
                            'diferencia' => $this->diferencia,
                            'id_unidad' => $this->obtenerIdFarmacia(),
                            'id_medicamento' => $this->idMed,
                            'fecha_vencimiento' => $this->fecha_vencimiento,
                            'id_user_created' => auth()->user()->id,
                        ]);

                        if ($cierre) {
                            $this->id_cierre = $cierre->id;
                            $stock_ant = MedicamentoStock::where('id_medicamento', $this->idMed)
                                ->orderBy('nro_lote', 'DESC')
                                ->first();
                            $this->precio_anterior = $stock_ant->precio_unitario;

                            if ($this->diferencia == 0) {
                                $this->sin_diferencia();
                            } elseif ($this->stock_actual < $this->cantidad_fisica) {
                                $this->ingreso_ajuste_contable();
                            } elseif ($this->stock_actual > $this->cantidad_fisica) {
                                $this->salida_ajuste_contable();
                            }


                            $this->dispatch('cierre_gestion_registrado');
                            $this->dispatch('pg:eventRefresh-CierreFarmaciaTable');
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
                        'cantidad_bajas'  => $this->cantidad_bajas,
                        'id_unidad' => $this->obtenerIdFarmacia(),
                        'id_medicamento' => $this->idMed,
                        'fecha_vencimiento' => $this->fecha_vencimiento,
                        'id_user_created' => auth()->user()->id,
                    ]);

                    if ($cierre) {
                        $this->id_cierre = $cierre->id;

                        if ($this->diferencia == 0) {
                            $this->sin_diferencia();
                        } elseif ($this->stock_actual < $this->cantidad_fisica) {
                            $this->ingreso_ajuste_contable();
                        } elseif ($this->stock_actual > $this->cantidad_fisica) {
                            $this->salida_ajuste_contable();
                        }

                        $this->dispatch('cierre_gestion_registrado');
                        $this->dispatch('pg:eventRefresh-CierreFarmaciaTable');
                    }
                }
            } else {
                $this->dispatch('complete_datos');
            }
            $this->skipRender();
        } catch (\Throwable $th) {
            $this->dispatch('cierre_error');
        }
    }

    public function sin_diferencia()
    {
        $movimiento = Movimiento::create([
            'id_corresponde' => $this->id_cierre,
            'id_identificador_tabla' => 4,
            'dato_solicitante' => 'CIERRE GESTIÓN',
            'nro_documento' => Carbon::now()->year,
            'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
            'dato_interesado' => 'FARMACIA',
            'cantidad_ingreso' => 0,
            'cantidad_salida' => 0,
            'saldo' => $this->stock_actual,
            'estado_validacion' => 'VALIDADO',
            'id_medicamento' => $this->idMed,
            'id_unidad' => $this->obtenerIdFarmacia(),
            'cierre_gestion' => true,
            'precio_unitario' => $this->precio_anterior,
            'observacion' => 'Sin Diferencias',
            'id_user_created' => auth()->user()->id,
        ]);
        if ($movimiento) {
        }
    }
    public function ingreso_ajuste_contable()
    {
        $movimiento = Movimiento::create([
            'id_corresponde' => $this->id_cierre,
            'id_identificador_tabla' => 4,
            'dato_solicitante' => 'CIERRE GESTIÓN',
            'nro_documento' => Carbon::now()->year,
            'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
            'dato_interesado' => 'FARMACIA',
            'cantidad_ingreso' => $this->ingreso,
            'cantidad_salida' => 0,
            'saldo' => $this->saldo,
            'estado_validacion' => 'VALIDADO',
            'id_medicamento' => $this->idMed,
            'id_unidad' => $this->obtenerIdFarmacia(),
            'cierre_gestion' => true,
            'precio_unitario' => $this->precio_anterior,
            'observacion' => 'Ingreso Ajuste Contable',
            'id_user_created' => auth()->user()->id,
        ]);
        if ($movimiento) {
        }
    }
    public function salida_ajuste_contable()
    {

        $movimiento = Movimiento::create([
            'id_corresponde' => $this->id_cierre,
            'id_identificador_tabla' => 4,
            'dato_solicitante' => 'CIERRE GESTIÓN',
            'nro_documento' => Carbon::now()->year,
            'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
            'dato_interesado' => 'FARMACIA',
            'cantidad_ingreso' => 0,
            'cantidad_salida' => $this->salida,
            'saldo' => $this->saldo,
            'estado_validacion' => 'VALIDADO',
            'id_medicamento' => $this->idMed,
            'id_unidad' => $this->obtenerIdFarmacia(),
            'cierre_gestion' => true,
            'precio_unitario' => $this->precio_anterior,
            'observacion' => 'Salida Ajuste Contable',
            'id_user_created' => auth()->user()->id,
        ]);
        if ($movimiento) {
        }
    } */
}
