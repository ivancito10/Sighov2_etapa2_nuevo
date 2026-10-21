<?php

namespace App\Livewire\Farmacia\Farmacia\Receta;

use Livewire\Component;
use App\Services\EspecialidadService;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use Livewire\Attributes\On;
use App\Models\ConsultaExterna\TipoConsulta;
use App\Models\Farmacia\MaxMinServicio;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Services\ServicioPersona;
use App\Services\ConsultaExterna\MedicamentosService;
use App\Services\ListaUnidades;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RecetaCreate extends Component
{

    #[Validate]
    public $datos_medico;
    public $id_especialista, $paciente, $matricula,
        $id_persona_paciente, $id_medicamento, $stock_farmacia, $cantidad,
        $id_tipo_atencion = null, $fecha_emision, $nro_receta_anterior, $tipo_atencion = [];
    public $medicamentos, $medicamentosNoVigentes, $fecha_maxima, $fecha_minima,
        $id_farmacia, $stock_actual, $listaEspecialistas;
    public $selectedMedicamento, $mostrarAdvertencia, $medicamentosSeleccionados = [],
        $array_id_receta, $observacion_quirofano = '';

    protected MedicamentosService $recetaService;

    public function render()
    {
        return view('livewire.farmacia.farmacia.receta.receta-create');
    }

    protected function rules()
    {
        return [
            'id_especialista' => 'required',
            'paciente' => 'required',
            'matricula' => 'required',
            'id_tipo_atencion' => 'required',
            'fecha_emision' => 'required',
            'nro_receta_anterior' => 'required',
        ];
    }

    public static function obtenerIdFarmacia()
    {
        /*$datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];*/
        return 76;
    }

    public function mount(MedicamentosService $recetaService)
    {

        //Para el texto enriquecido
        $this->dispatch('cargar-observacion-inicial', $this->observacion_quirofano);

        $this->recetaService = $recetaService;
        $this->mostrarMedicos();
        $this->mostrarTipoAtencion();

        $esp = new EspecialidadService();
        $this->listaEspecialistas = $esp->obtenerEspecialistas();

        $this->fecha_maxima = now()->format('Y-m-d');
        $this->fecha_emision = $this->fecha_maxima;

        $datosUnidades = new ListaUnidades();
        $this->id_farmacia = collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
        $this->actualizarMedicamentos();
    }

    public function mostrarMedicos()
    {

        $medicos = new EspecialidadService();
        $this->datos_medico = $medicos->obtenerEspecialistas();
    }

    public function mostrarTipoAtencion()
    {
        $this->tipo_atencion =  TipoConsulta::all();
    }

    public function botonModalPersona()
    {
        $this->dispatch('modalPersonasLista');
    }

    #[On('asignarPersona')]
    public function asignarPersona($data)
    {
        //dd($data);

        //Preguntar servicio OBTENER PERSONA FARMACIA
        $dato_per = new ServicioPersona();
        //dd($id_persona);
        $datos_persona = $dato_per->datosPersona($data);

        //dd($datos_persona);
        if ($datos_persona == null) {
            $this->dispatch('personaNoAsegurada');
            return $this->dispatch('cerrarModalPersonasLista');
        }
        $this->id_persona_paciente = $data;
        $persona = Persona::where('id', $data)->first();
        $this->paciente =  implode(' ', array_filter([$persona->p_apellido, $persona->s_apellido, $persona->nombres]));
        $this->matricula = $persona->matricula_seguro;
        $this->dispatch('personaSeleccionada', nombrePaciente: $this->paciente);
        $this->dispatch('cerrarModalPersonasLista');
    }


    public function agregarMedicamento()
    {
        //dd($this->id_medicamento, $this->cantidad, $this->id_tipo_atencion, $this->fecha_emision, $this->nro_receta_anterior);
        $this->validate([
            'id_medicamento' => 'required',
            'cantidad'       => 'required|integer|min:1',
        ]);
        $this->selectedMedicamento = $this->id_medicamento['value'];

        if (count($this->medicamentosSeleccionados) >= 3) {
            $this->mostrarAdvertencia = true;
            $this->dispatch('abrirToast3Medicamentos');
        } else {
            $this->mostrarAdvertencia = false;
        }
        // $consumoDia   = $this->CalculoConsumoPorDia($this->selectedIndicacion);
        // $this->diasValidez  = (int) floor(($this->cantidad / $consumoDia) * 0.75);
        $esNoVigente = collect($this->medicamentosNoVigentes)->contains('id_medicamento', $this->selectedMedicamento);
        if ($esNoVigente) {
            return $this->dispatch('medicamentoNovigente');
        }
        //$this->selectedMedicamento = (int) $this->selectedMedicamento;
        $med = collect($this->medicamentos)->firstWhere('id', $this->selectedMedicamento);
        //Verificar cantidad maximo para medicamento segun tipo atencion
        if ($this->cantidad > $med['stock_actual']) {
            return $this->dispatch('stockInsuficiente');
        }
        // $cantidad_maxima = MaxMinServicio::where('id_medicamento', $med['id'])
        //     ->where('id_tipo_consulta', 1) // 1 = consulta_externa
        //     ->pluck('cantidad_maxima')
        //     ->first();

        // if ($cantidad_maxima !== null) {
        //     if ($this->cantidad > (int)$cantidad_maxima) {
        //         return $this->dispatch('CantidadExcedida', cantidad_maxima: $cantidad_maxima);
        //     }
        // }

        $nuevoItem = [
            'medicamento_id' => $med['id'],
            'medicamento'    => $med['nombre'],
            'codigo'    => $med['codigo_medicamento'],
            'restringido'    => $med['restringido'],
            'cantidad'       => $this->cantidad,
            //'indicaciones'   => $this->selectedIndicacion,
            //'forma_adm'      => $this->forma_adm,
            //'dias_validez'   => $this->diasValidez,
            //'recomendacion'  => $this->recomendacion,
            'forma_farmaceutica' => $med['forma_farmaceutica'],
        ];

        // 2) Comprueba duplicado por id en la lista seleccionada
        if (collect($this->medicamentosSeleccionados)
            ->pluck('medicamento_id')
            ->contains($med['id'])
        ) {
            return $this->dispatch('medicamentoYaRegistrado');
        }

        // Agrega el nuevo medicamento
        $this->medicamentosSeleccionados[] = $nuevoItem;
        $this->reset(['cantidad', 'id_medicamento', 'stock_actual']);
        $this->dispatch('medicamentoRegistrado');
    }



    public function stockActualMed()
    {
        //dd('llega',$this->id_medicamento); 
        $this->selectedMedicamento = $this->id_medicamento['value'];
        $med = collect($this->medicamentos)->firstWhere('id', $this->selectedMedicamento);
        $this->stock_actual = $med['stock_actual'];
    }

    public function actualizarMedicamentos()
    {
        // $datoPersona = $this->id_persona_paciente;

        // // 1. Obtener recetas anteriores
        // $recetasAnteriores = Receta::where('id_persona', $datoPersona)
        //     ->orderByDesc('created_at')
        //     ->get(['id', 'id_especialista', 'nro_receta']);

        // // 2. Obtener todos los medicamentos relacionados con esas recetas
        // $idsRecetas = $recetasAnteriores->pluck('id');
        // $medicamentosAgrupados = RecetaMedicamento::whereIn('id_receta', $idsRecetas)
        //     ->with('medicamentoRecetaMedicamento')
        //     ->get(['id_receta', 'id_medicamento', 'dias_validez', 'created_at', 'fecha_ini', 'fecha_fin','estado_validez'])
        //     ->groupBy('id_receta');

        // // 3. Procesar medicamentos no vigentes
        // $medicamentosNoVigentes = [];

        // foreach ($recetasAnteriores as $receta) {
        //     $especialista = $this->listaEspecialistas->firstWhere('id', $receta->id_especialista);

        //     $nombreEspecialista = trim(
        //         ($especialista['persona_especialista']['nombres'] ?? '') . ' ' .
        //         ($especialista['persona_especialista']['p_apellido'] ?? '') . ' ' .
        //         ($especialista['persona_especialista']['s_apellido'] ?? '')
        //     );
        //     //dd($receta);
        //     $especialidad = $especialista['especialidad_especialista']['especialidad'] ?? '';

        //     foreach ($medicamentosAgrupados[$receta->id] ?? [] as $medicamento) {
        //         //dd($medicamento);
        //         if (now()->lessThan($medicamento->fecha_fin) && $medicamento->estado_validez) {
        //             $medicamentosNoVigentes[] = [
        //                 'id_medicamento' => $medicamento->id_medicamento,
        //                 'dias_validez' => $medicamento->dias_validez,
        //                 'created_at' => $medicamento->created_at,
        //                 'fecha_ini' => $medicamento->fecha_ini,
        //                 'fecha_fin' => $medicamento->fecha_fin,
        //                 'especialista_nombre' => $nombreEspecialista,
        //                 'especialista_especialidad' => $especialidad,
        //                 'medicamento_receta_medicamento' => $medicamento->medicamentoRecetaMedicamento,
        //                 'nro_receta' => $receta->nro_receta
        //             ];
        //         }
        //     }
        // }

        // // 4. Filtrar medicamentos vigentes
        // $idsNoVigentes = collect($medicamentosNoVigentes)->pluck('id_medicamento')->unique()->values();

        //$this->medicamentos = Medicamento::whereNotIn('id', $idsNoVigentes)->get()
        $this->medicamentos = Medicamento::where('estado', true)->get()
            ->where('habilitado_farmacia', true)
            ->map(function ($med) {
                //dd($med->id,$this->id_farmacia);
                $med->stock_actual = (int)$this->recetaService->stockActual($med->id, $this->id_farmacia) ?? null;
                return $med;
            })->toArray();
        //dd($this->medicamentos);
        // 5. Guardar medicamentos no vigentes
        //$this->medicamentosNoVigentes = $medicamentosNoVigentes;
    }

    #[On('funcionGuardarRecetaManual')]
    public function funcionGuardarRecetaManual($observacion_quirofano = null)
    {
        // dd('entra');
        $this->validate();
        try {

            DB::beginTransaction();
            $this->array_id_receta = [];
            $this->observacion_quirofano = $observacion_quirofano;
            // Normalizar índices por si hubo eliminaciones en el array
            $this->medicamentosSeleccionados = array_values($this->medicamentosSeleccionados);

            if (!empty($this->medicamentosSeleccionados)) {
                // Obtener número máximo de medicamentos por receta
                // $num_max_med = TipoConsulta::where('nombre', 'CONSULTA EXTERNA')
                //     ->pluck('max_med_receta')
                //     ->first();

                // Dividir TODOS los medicamentos (restringidos o no) en bloques
                //$bloques = array_chunk($this->medicamentosSeleccionados, $num_max_med);

                //foreach ($bloques as $bloque) {
                $receta = $this->crearRecetaBase($this->observacion_quirofano);
                // dd($receta);
                $id_receta = $receta->id;
                $this->array_id_receta[] = $id_receta;
                // dd($this->array_id_receta);
                foreach ($this->medicamentosSeleccionados as $item) {
                    $receta->medicamentosReceta()->attach($item['medicamento_id'], [
                        'cantidad'        => $item['cantidad'],
                        'indicaciones'    => $item['indicaciones'] ?? null,
                        'forma_adm'       => $item['forma_adm'] ?? null,
                        'recomendacion'   => $item['recomendacion'] ?? null,
                        'dias_validez'    => $item['dias_validez'] ?? null,
                        'fecha_ini'       => $item['fecha_ini'] ?? null,
                        'fecha_fin'       => Carbon::now(),
                        'id_user_created' => auth()->id(),
                        'estado_validez'  => false,
                        // si en el pivot tienes un campo para marcar restringido:
                        //'restringido'     => $item['restringido'] ?? false,
                    ]);

                    // Datos para movimientos
                    $datosNecesariosParaMovimientos = [
                        'id_corresponde' => $id_receta,
                        'id_unidad'      => $this->obtenerIdFarmacia(),
                        'nro_documento'  => $receta->nro_receta,
                    ];

                    $this->procesarLotesEntrega(
                        $item['medicamento_id'],
                        $item['cantidad'],
                        $datosNecesariosParaMovimientos
                    );
                }
                //}
            }

            // Enviar TODO al PDF (puede ser uno o varios ids, pero en una sola llamada)
            $ids_string = implode(',', $this->array_id_receta);
            $this->js('window.open("/consulta_externa/pdf/receta/' . $ids_string . '", "_blank")');

            DB::commit();

            // Limpiar y notificar
            $this->limpiarDatos();
            $this->medicamentosSeleccionados = [];
            $this->dispatch('recetaCreada');
            $this->dispatch('pg:eventRefresh-RecetasFarmaciaTable');
            $this->dispatch('cerrar_modal_receta');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error guardando receta: ' . $e->getMessage());
            //dd($e); // si quieres debug
            $this->dispatch('error');
        }
    }


    private function crearRecetaBase($obs_quirofano)
    {
        //dd($this->fecha_emision);
        try {
            $esp = $this->listaEspecialistas->firstWhere('id', $this->id_especialista['value']);
            // $inicioAnio = Carbon::now()->startOfYear();
            // $finAnio    = Carbon::now()->endOfYear();

            // $ultimo = Receta::whereBetween('created_at', [$inicioAnio, $finAnio])
            //     ->orderBy('nro_receta', 'desc')
            //     ->lockForUpdate()
            //     ->value('nro_receta');

            // $siguiente = $ultimo ? $ultimo + 1 : 1;

            // $ultimoRecetaManual = Receta::where('manual', true)
            //     ->orderBy('nro_receta_manual', 'asc')
            //     ->first();

            // $siguienteManual = 1;
            // if ($ultimoRecetaManual) {
            //     $siguienteManual = $ultimoRecetaManual->nro_receta_manual ? $ultimoRecetaManual->nro_receta_manual + 1 : 1;
            // }

            $datosReceta = [
                //'nro_receta'          => $siguiente,
                'fecha_entrega'       => now(),
                'fecha_transcripcion' => $this->fecha_emision,
                'manual'              => true,
                'id_tipo_consulta'    => $this->id_tipo_atencion,
                'id_especialista'     => $this->id_especialista['value'],
                'id_historia_clinica' => null,
                'id_persona'          => $this->id_persona_paciente,
                'estado'              => false,
                'id_persona_especialista' => $esp['persona_especialista']['id'] ?? null,
                'id_estado_validacion' => 2,
                'id_persona_transcribe' => auth()->user()->personaUser->id,
                'observacion_quirofano' => $obs_quirofano,
                'nro_receta_manual' => $this->nro_receta_anterior,
                'id_user_created'     => auth()->id(),
                'created_at'          => now(),
                'clave_unica'         => $this->generarClaveUnica(),
            ];
            //dd($datosReceta);
            $crearNuevaRecetaManual = Receta::create($datosReceta);

            return Receta::find($crearNuevaRecetaManual->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error guardando receta: ' . $e->getMessage());
            dd($e); // si quieres debug
            $this->dispatch('error');
        }
    }

    private function generarClaveUnica()
    {
        $iniciales = 'YRLRBTE';
        $secreto   = 'YRLRBTE-CLAVE-NUESTRA';

        $fechaHora = Carbon::now()->format('Y-m-d H:i:s.u');

        $base = "{$iniciales}|{$fechaHora}|{$secreto}";

        $hash = hash('sha256', $base);

        $corto = strtoupper(substr($hash, 0, 16));

        return implode('-', str_split($corto, 4));  //ESTO ES PARA DARLE FORMATITO TIPO: XXXX-XXXX-XXXX-XXXX
    }


    public function eliminarReceta($index)
    {
        unset($this->medicamentosSeleccionados[$index]);
        $this->medicamentosSeleccionados = array_values($this->medicamentosSeleccionados);
    }

    public function updatedIdTipoAtencion($value)
    {
        // if ((int) $value === 6) {
        //     $this->dispatch('inicializarQuill', valorInicial: $this->observacion_quirofano);
        // }
    }

    #[On('limpiarDatosJs')]
    public function limpiarDatos()
    {
        $this->reset(
            'paciente',
            'matricula',
            'id_especialista',
            'id_tipo_atencion',
            'nro_receta_anterior',
            'observacion_quirofano',
            'id_medicamento',
            'stock_actual',
            'cantidad'
        );
        $this->dispatch('limpiarSelectEspecialista');
    }

    private function procesarLotesEntrega(int $idMedicamento, int $cantidad, array $datosNecesarios)
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

            $datosEspecialista = collect($this->datos_medico)
                ->firstWhere('id', $this->id_especialista['value']);
            $nombreEspecialista = implode(' ', array_filter([
                $datosEspecialista['persona_especialista']['p_apellido'],
                $datosEspecialista['persona_especialista']['s_apellido'],
                $datosEspecialista['persona_especialista']['nombres'],
            ]));

            $datosParaMovimientos = [
                'id_corresponde' => $datosNecesarios['id_corresponde'],
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $datosNecesarios['id_unidad'],
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $this->paciente,
                'nro_documento' => $datosNecesarios['nro_documento'],
                'dato_interesado' => $nombreEspecialista,
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
        ]);

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }
}
