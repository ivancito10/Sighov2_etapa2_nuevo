<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Receta;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Models\ConsultaExterna\TipoConsulta;
use App\Models\Farmacia\MaxMinServicio;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Services\ConsultaExterna\MedicamentosService;
use App\Services\EspecialidadService;
use App\Services\ListaUnidades;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistrarReceta extends Component
{
    public $datoHistoriaClinica;

    public $plan = 1; // 1= receta

    public $medicamentos;

    public $recetaCreada = null;

    public $selectedMedicamento;

    public $selectedIndicacion = '';

    public $cantidad;

    public $diasValidez;

    public $forma_adm = '';

    public $recomendacion;

    public $stock_actual;

    public $medicamentosNoVigentes = [];

    public $listaEspecialistas;

    public $mostrarAdvertencia = false;

    public $array_id_receta = [];

    public $tipo_consulta = null;

    public $id_farmacia;

    public $fecha_proxima_consulta;

    protected MedicamentosService $recetaService;

    //public $medicamentosRestringidos = [];

    public $medicamentosSeleccionados = [];

    public $medicamentosSeleccionadosGeneral = [];
    public $validacion_indicaciones = false;
    public  $validacion_forma_administracion = true;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.receta.registrar-receta');
    }

    public function mount(MedicamentosService $recetaService)
    {
        $this->recetaService = $recetaService;
        //$datosUnidades = new ListaUnidades();
        //$this->id_farmacia = collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
        $this->id_farmacia = 76;

        //$this->medicamentos = Medicamento::all();

        $esp = new EspecialidadService();
        $this->listaEspecialistas = $esp->obtenerEspecialistas();
        $this->actualizarMedicamentos();
        $this->tipo_consulta = request()->query('tipo_at');
        //dd($this->selectedIndicacion);
    }

    /*   protected $rules = [

        'recomendacion' => 'nullable|string',
        'selectedMedicamento' => 'required',
        'cantidad' => 'required|integer|min:1',
        'selectedIndicacion' => 'required|nullable|string',
        'forma_adm' => $this->validacion_forma_administracion ? 'required' : 'nullable',
    ]; */

    protected function rules()
    {
        return [
            'recomendacion'      => $this->validacion_indicaciones ? 'required' : 'nullable',
            'selectedMedicamento' => 'required',
            'cantidad'            => 'required|integer|min:1',
            'selectedIndicacion'  => 'required',
            'forma_adm'           => $this->validacion_forma_administracion ? 'required' : 'nullable',
            'fecha_proxima_consulta' => 'nullable|date|after:today',
            'diasValidez' => 'required|integer',
        ];
    }

    public static function obtenerIdFarmacia()
    {
        //$datosUnidades = new ListaUnidades();

        //return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
        return 76;
    }

    #[On('recibirHCReceta')]
    public function recibirHistoriaClinica($data)
    {
        $this->datoHistoriaClinica = $data;
    }

    public function updatedCantidad()
    {
        $this->diasValidezMedicamento();
    }

    public function diasValidezMedicamento()
    {
        //dd('sdsda');
        //dd($this->selectedIndicacion, $this->cantidad);
        if ($this->selectedIndicacion != 'Seguir indicaciones') {
            if ($this->selectedIndicacion != null && $this->cantidad != null) {
                $consumoDia = $this->CalculoConsumoPorDia($this->selectedIndicacion);
                $this->diasValidez = (int) floor(($this->cantidad / $consumoDia) * 0.75);
            }
            $this->validacion_forma_administracion = true;
            $this->validacion_indicaciones = false;
        } else {
            $this->diasValidez = 0;
            $this->validacion_forma_administracion = false;
            $this->validacion_indicaciones = true;
        }
    }

    public function stockActualMed()
    {
        //dd('llega');
        $med = collect($this->medicamentos)->firstWhere('id', $this->selectedMedicamento);
        //Verificar cantidad maximo para medicamento segun tipo atencion
        if ($med['stock_actual'] == 0) {
            return $this->dispatch('stockInsuficiente');
        }
        $esNoVigente = collect($this->medicamentosNoVigentes)->contains('id_medicamento', $this->selectedMedicamento);
        if ($esNoVigente) {
            return $this->dispatch('medicamentoNovigente');
        }
        $this->stock_actual = $med['stock_actual'] ?? null;
    }

    public function registrarMedicamento()
    {
        $this->validate();
        // dd('llega');
        if (count($this->medicamentosSeleccionados) >= 3) {
            $this->mostrarAdvertencia = true;
            $this->dispatch('abrirToast3Medicamentos');
        } else {
            $this->mostrarAdvertencia = false;
        }
        // $consumoDia   = $this->CalculoConsumoPorDia($this->selectedIndicacion);
        // $this->diasValidez  = (int) floor(($this->cantidad / $consumoDia) * 0.75);

        //$this->selectedMedicamento = (int) $this->selectedMedicamento;
        $med = collect($this->medicamentos)->firstWhere('id', $this->selectedMedicamento);

        if ($this->cantidad > $med['stock_actual']) {
            return $this->dispatch('stockInsuficiente');
        }

        $cantidad_maxima = MaxMinServicio::where('id_medicamento', $med['id'])
            ->where('id_tipo_consulta', $this->tipo_consulta) // 1 = consulta_externa
            ->pluck('cantidad_maxima')
            ->first();

        if ($cantidad_maxima !== null) {
            if ($this->cantidad > (int) $cantidad_maxima) {
                return $this->dispatch('CantidadExcedida', cantidad_maxima: $cantidad_maxima);
            }
        }

        //dd($this->diasValidez, trim($this->diasValidez));

        $nuevoItem = [
            'medicamento_id' => $med['id'],
            'medicamento' => $med['nombre'],
            'concentracion' => $med['concentracion'],
            'codigo' => $med['codigo_medicamento'],
            'restringido' => $med['restringido'],
            'cantidad' => $this->cantidad,
            'indicaciones' => $this->selectedIndicacion,
            'forma_adm' => $this->forma_adm,
            'dias_validez' => $this->diasValidez,
            'recomendacion' => $this->recomendacion,
            'forma_farmaceutica' => $med['forma_farmaceutica'],
        ];

        // 1) Comprueba si el medicamento ya está en la lista general
        if (collect($this->medicamentosSeleccionadosGeneral)
            ->pluck('medicamento_id')
            ->contains($med['id'])
        ) {
            return $this->dispatch('medicamentoYaRegistrado');
        }

        // 2) Comprueba duplicado por id en la lista seleccionada
        if (collect($this->medicamentosSeleccionados)
            ->pluck('medicamento_id')
            ->contains($med['id'])
        ) {
            return $this->dispatch('medicamentoYaRegistrado');
        }

        // Agrega el nuevo medicamento
        $this->medicamentosSeleccionados[] = $nuevoItem;
        // dd($this->validacion_indicaciones, $this->validacion_forma_administracion);
        $this->reset(['cantidad', 'selectedIndicacion', 'forma_adm', 'recomendacion', 'diasValidez', 'stock_actual', 'validacion_indicaciones', 'validacion_forma_administracion',]);
        $this->dispatch('medicamentoRegistrado');
    }

    public function removeMedicamento($index)
    {
        unset($this->medicamentosSeleccionados[$index]);
        $this->medicamentosSeleccionados = array_values($this->medicamentosSeleccionados);
    }

    public function CalculoConsumoPorDia($indicacion)
    {
        switch ($indicacion) {
            case 'Cada 24 Hrs.':
            case '1 vez al dia':
                return 1;

            case 'Cada 12 Hrs.':
            case '2 veces al dia':
                return 2;

            case 'Cada 8 Hrs.':
            case '3 veces al dia':
                return 3;

            case 'Cada 6 Hrs.':
                return 4;

            case 'Cada 4 Hrs.':
                return 6;

            case 'Cada 2 Hrs.':
                return 12;

            case '1 vez por semana':
                return 0.143;

            case '2 veces por semana':
                return 0.0714;
            case 'Seguir indicaciones':
                return null;
            default:
                return null;
        }
    }

    #[On('guardarReceta')]
    public function guardarReceta()
    {
        //dd($this->fecha_proxima_consulta);
        if (trim($this->fecha_proxima_consulta) === '') {
            $this->fecha_proxima_consulta = null;
        }
        try {
            //$this->validate();
            if (empty($this->medicamentosSeleccionados)) {
                $this->dispatch('sinMedicamento');
                return;
            }
            // Separar medicamentos restringidos
            $medicamentosRestringidos = [];
            foreach ($this->medicamentosSeleccionados as $item) {
                if ($item['restringido']) {
                    $medicamentosRestringidos[] = $item;
                }
            }
            // Filtrar los comunes
            $medicamentosComunes = array_filter(
                $this->medicamentosSeleccionados,
                fn($item) => ! $item['restringido']
            );
            $this->medicamentosSeleccionados = array_values($this->medicamentosSeleccionados);

            DB::beginTransaction();

            if (! empty($medicamentosComunes)) {
                //Obtener numero maximo de medicamentos por receta
                $num_max_med = TipoConsulta::where('id', $this->tipo_consulta)->pluck('max_med_receta')->first();

                $bloques = array_chunk($medicamentosComunes, $num_max_med);
                foreach ($bloques as $bloque) {
                    $receta = $this->crearRecetaBase();
                    $id_receta = $receta->id;
                    $this->array_id_receta[] = $id_receta;

                    foreach ($bloque as $item) {
                        $receta->medicamentosReceta()->attach($item['medicamento_id'], [
                            'cantidad' => $item['cantidad'],
                            'indicaciones' => $item['indicaciones'],
                            'forma_adm' => $item['forma_adm'],
                            'recomendacion' => $item['recomendacion'] ?? null,
                            'dias_validez' => $item['dias_validez'],
                            'fecha_ini' => now()->format('Y-m-d'),
                            'fecha_fin' => now()->addDays($item['dias_validez'])->format('Y-m-d'),
                            'id_user_created' => auth()->id(),
                            'estado_validez' => true,
                        ]);

                        //APLICAR RESTA EN MOVIMIENTOS AQUI

                        /*$datosNecesariosParaMovimientos = [
                            'id_corresponde' => $id_receta,
                            'id_unidad' => $this->obtenerIdFarmacia(),
                            'nro_documento' => $receta->nro_receta,
                        ];

                        $this->procesarLotesEntrega($item['medicamento_id'], $item['cantidad'], $datosNecesariosParaMovimientos);*/
                    }
                    $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan, $id_receta);
                }
            }
            // Recetas individuales por medicamento restringido
            foreach ($medicamentosRestringidos as $item) {
                $recetaRestr = $this->crearRecetaBase();
                $id_receta_restr = $recetaRestr->id;
                $this->array_id_receta[] = $id_receta_restr;

                $recetaRestr->medicamentosReceta()->attach($item['medicamento_id'], [
                    'cantidad' => $item['cantidad'],
                    'indicaciones' => $item['indicaciones'],
                    'forma_adm' => $item['forma_adm'],
                    'recomendacion' => $item['recomendacion'] ?? null,
                    'dias_validez' => $item['dias_validez'],
                    'fecha_ini' => now()->format('Y-m-d'),
                    'fecha_fin' => now()->addDays($item['dias_validez'])->format('Y-m-d'),
                    'id_user_created' => auth()->id(),
                    'estado_validez' => true,
                ]);

                //APLICAR RESTA EN MOVIMIENTOS AQUI

                /*$datosNecesariosParaMovimientos = [
                    'id_corresponde' => $id_receta_restr,
                    'id_unidad' => $this->obtenerIdFarmacia(),
                    'nro_documento' => $recetaRestr->nro_receta,
                ];

                $this->procesarLotesEntrega($item['medicamento_id'], $item['cantidad'], $datosNecesariosParaMovimientos);*/

                $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan, $id_receta_restr);
            }

            $ids_string = implode(',', $this->array_id_receta);
            $this->js('window.open("/consulta_externa/pdf/receta/' . $ids_string . '", "_blank")');

            // recorrer cada ID y abrir individualmente
            foreach ($this->array_id_receta as $id_receta) {
                //dd($this->array_id_receta, $id_receta);
                $this->js('window.open("/consulta_externa/pdf/receta_ftp/' . $id_receta . '", "_blank")');
            }

            DB::commit();

            // Limpiar y notificar
            $this->medicamentosSeleccionadosGeneral += $this->medicamentosSeleccionados;
            $this->reset('fecha_proxima_consulta');
            $this->medicamentosSeleccionados = [];
            $this->dispatch('pg:eventRefresh-RecetasPersonaTable');
            $this->dispatch('recetaCreada');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            Log::error('Error guardando receta: ' . $e->getMessage());
            $this->dispatch('error');
        }
    }

    private function crearRecetaBase()
    { 

        $esp = $this->listaEspecialistas->firstWhere('id', $this->datoHistoriaClinica['id_especialista']);
        // $inicioAnio = Carbon::now()->startOfYear();
        // $finAnio = Carbon::now()->endOfYear();

        //$ultimo = Receta::whereBetween('created_at', [$inicioAnio, $finAnio])
        // ->orderBy('nro_receta', 'desc')
        // ->lockForUpdate()
        // ->value('nro_receta');

        //$siguiente = $ultimo ? $ultimo + 1 : 1;

        $datosReceta = [
            //'nro_receta' => $siguiente,
            'fecha_entrega' => null,
            'fecha_transcripcion' => now(),
            'manual' => false,
            'id_tipo_consulta' => $this->tipo_consulta,
            'id_especialista' => $this->datoHistoriaClinica['id_especialista'],
            'id_historia_clinica' => $this->datoHistoriaClinica['id'],
            'id_persona' => $this->datoHistoriaClinica['id_persona'],
            'estado' => false,
            'id_persona_especialista' => $esp['persona_especialista']['id'] ?? null,
            'id_estado_validacion' => 1,
            'fecha_caducidad' => Carbon::now()->addDays(2),
            'id_user_created' => auth()->id(),
            'created_at' => now(),
            'clave_unica' => $this->generarClaveUnica(),
            'fecha_proxima_consulta' => $this->fecha_proxima_consulta,
        ];
        $crearNuevaReceta = Receta::create($datosReceta);

        //dd(Receta::find($crearNuevaReceta->id));
        return Receta::find($crearNuevaReceta->id);
    }

    private function generarClaveUnica()
    {
        $iniciales = 'YRLRBTE';
        $secreto = 'YRLRBTE-CLAVE-NUESTRA';

        $fechaHora = Carbon::now()->format('Y-m-d H:i:s.u');

        $base = "{$iniciales}|{$fechaHora}|{$secreto}";

        $hash = hash('sha256', $base);

        $corto = strtoupper(substr($hash, 0, 16));

        return implode('-', str_split($corto, 4));  //ESTO ES PARA DARLE FORMATITO TIPO: XXXX-XXXX-XXXX-XXXX
    }

    public function registrarTablaHCPlanes($id_hc, $id_plan, $id_corresponde)
    {
        try {
            $datos = [
                'id_historia_clinica' => $id_hc,
                'id_plan' => $id_plan,
                'id_corresponde' => $id_corresponde,
                'id_user_created' => auth()->id(),
                'created_at' => now(),
            ];

            $HCplanCreado = HCPlanes::create($datos);
            if ($HCplanCreado) {
                if ($this->tipo_consulta != 1) {
                    $this->dispatch('actualizarPlanes')->to(RegistroSoap::class);
                } else {
                    $this->dispatch('actualizarPlanes')->to(ConsultaExternaRegister::class);
                }
            } else {
                $this->dispatch('error');
            }
        } catch (\Throwable $e) {
            Log::error('Error al registrar en HC_planes', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);
            $this->dispatch('error');
        }
    }

    public function verMedicamentosRestringidos()
    {
        //dd($this->medicamentosNoVigentes);
        $this->dispatch('abrirModalVerMedicamentosRest', $this->medicamentosNoVigentes);
    }

    public function cerrarModalMedRest()
    {
        $this->dispatch('cerrarModalMedicamentosRest');
    }

    public function actualizarMedicamentos()
    {
        $datoPersona = session('agenda_data');
        // 1. Obtener recetas anteriores
        // $datosMedicamentos = new MedicamentosService();
        // $this->recetaService = $datosMedicamentos;
        $id_persona = $datoPersona['id_persona'] ?? null;
        $recetasAnteriores = [];
        $medicamentosAgrupados = [];
        if ($id_persona) {
            $recetasAnteriores = Receta::where('id_persona', $id_persona)
                ->orderByDesc('created_at')
                ->get(['id', 'id_especialista', 'nro_receta']);

            $idsRecetas = $recetasAnteriores->pluck('id');
            $medicamentosAgrupados = RecetaMedicamento::whereIn('id_receta', $idsRecetas)
                ->with(['medicamentoRecetaMedicamento' => function ($query) {
                    $query->where('estado', true);
                }])
                ->get(['id_receta', 'id_medicamento', 'dias_validez', 'created_at', 'fecha_ini', 'fecha_fin', 'estado_validez'])
                ->groupBy('id_receta');
        }
        // 2. Obtener todos los medicamentos relacionados con esas recetas

        //dd($medicamentosAgrupados);
        // 3. Procesar medicamentos no vigentes
        $medicamentosNoVigentes = [];
        foreach ($recetasAnteriores as $receta) {
            $especialista = $this->listaEspecialistas->firstWhere('id', $receta->id_especialista);

            $nombreEspecialista = trim(
                ($especialista['persona_especialista']['nombres'] ?? '') . ' ' .
                    ($especialista['persona_especialista']['p_apellido'] ?? '') . ' ' .
                    ($especialista['persona_especialista']['s_apellido'] ?? '')
            );
            //dd($receta);
            $especialidad = $especialista['especialidad_especialista']['especialidad'] ?? '';
            foreach ($medicamentosAgrupados[$receta->id] ?? [] as $medicamento) {
                if (now()->lessThan($medicamento->fecha_fin) && $medicamento->estado_validez) {
                    $medicamentosNoVigentes[] = [
                        'id_medicamento' => $medicamento->id_medicamento,
                        'dias_validez' => $medicamento->dias_validez,
                        'created_at' => $medicamento->created_at,
                        'fecha_ini' => $medicamento->fecha_ini,
                        'fecha_fin' => $medicamento->fecha_fin,
                        'especialista_nombre' => $nombreEspecialista,
                        'especialista_especialidad' => $especialidad,
                        'medicamento_receta_medicamento' => $medicamento->medicamentoRecetaMedicamento,
                        'nro_receta' => $receta->nro_receta,
                    ];
                }
            }
        }
        //dd($medicamentosNoVigentes);
        // 4. Filtrar medicamentos vigentes
        $idsNoVigentes = collect($medicamentosNoVigentes)->pluck('id_medicamento')->unique()->values();

        $this->medicamentos = Medicamento::where('estado', true)
            ->where('habilitado_farmacia', true)
            ->get()
            ->map(function ($med) use ($idsNoVigentes) {
                $med->stock_actual = (int) $this->recetaService->stockActual($med->id, $this->id_farmacia) ?? null;
                $med->no_vigente = $idsNoVigentes->contains($med->id); // 👈 flag para saber si está en tratamiento

                return $med;
            })
            ->toArray();

        $this->medicamentosNoVigentes = $medicamentosNoVigentes;
    }

    public function actualizarMedicamentosManual()
    {
        $datoPersona = session('agenda_data');
        // 1. Obtener recetas anteriores
        $datosMedicamentos = new MedicamentosService();
        $this->recetaService = $datosMedicamentos;

        $recetasAnteriores = Receta::where('id_persona', $datoPersona['id_persona'])
            ->orderByDesc('created_at')
            ->get(['id', 'id_especialista', 'nro_receta']);

        // 2. Obtener todos los medicamentos relacionados con esas recetas
        $idsRecetas = $recetasAnteriores->pluck('id');
        $medicamentosAgrupados = RecetaMedicamento::whereIn('id_receta', $idsRecetas)
            ->with(['medicamentoRecetaMedicamento' => function ($query) {
                $query->where('estado', true);
            }])
            ->get(['id_receta', 'id_medicamento', 'dias_validez', 'created_at', 'fecha_ini', 'fecha_fin', 'estado_validez'])
            ->groupBy('id_receta');

        // 3. Procesar medicamentos no vigentes
        $medicamentosNoVigentes = [];

        foreach ($recetasAnteriores as $receta) {
            $especialista = $this->listaEspecialistas->firstWhere('id', $receta->id_especialista);

            $nombreEspecialista = trim(
                ($especialista['persona_especialista']['nombres'] ?? '') . ' ' .
                    ($especialista['persona_especialista']['p_apellido'] ?? '') . ' ' .
                    ($especialista['persona_especialista']['s_apellido'] ?? '')
            );
            //dd($receta);
            $especialidad = $especialista['especialidad_especialista']['especialidad'] ?? '';

            foreach ($medicamentosAgrupados[$receta->id] ?? [] as $medicamento) {
                //dd($medicamento);
                if (now()->lessThan($medicamento->fecha_fin) && $medicamento->estado_validez) {
                    $medicamentosNoVigentes[] = [
                        'id_medicamento' => $medicamento->id_medicamento,
                        'dias_validez' => $medicamento->dias_validez,
                        'created_at' => $medicamento->created_at,
                        'fecha_ini' => $medicamento->fecha_ini,
                        'fecha_fin' => $medicamento->fecha_fin,
                        'especialista_nombre' => $nombreEspecialista,
                        'especialista_especialidad' => $especialidad,
                        'medicamento_receta_medicamento' => $medicamento->medicamentoRecetaMedicamento,
                        'nro_receta' => $receta->nro_receta,
                    ];
                }
            }
        }

        // 4. Filtrar medicamentos vigentes
        $idsNoVigentes = collect($medicamentosNoVigentes)->pluck('id_medicamento')->unique()->values();

        $this->medicamentos = Medicamento::where('estado', true)
            ->where('habilitado_farmacia', true)
            ->get()
            ->map(function ($med) use ($idsNoVigentes) {
                $med->stock_actual = (int) $this->recetaService->stockActual($med->id, $this->id_farmacia) ?? null;
                $med->no_vigente = $idsNoVigentes->contains($med->id); // 👈 flag para saber si está en tratamiento

                return $med;
            })
            ->toArray();
        //dd($this->medicamentos);
        $this->medicamentosNoVigentes = $medicamentosNoVigentes;
        $this->dispatch('medicamentosActualizados');
    }

    /*private function procesarLotesEntrega(int $idMedicamento, int $cantidad, array $datosNecesarios)
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

            $datosEspecialista = collect($this->listaEspecialistas)
                ->firstWhere('id', $this->datoHistoriaClinica['id_especialista']);
            $nombreEspecialista = implode(' ', array_filter([
                $datosEspecialista['persona_especialista']['p_apellido'],
                $datosEspecialista['persona_especialista']['s_apellido'],
                $datosEspecialista['persona_especialista']['nombres'],
            ]));

            $personaPaciente = Persona::find($this->datoHistoriaClinica['id_persona']);
            $nombrePaciente = implode(' ', array_filter([
                $personaPaciente->p_apellido,
                $personaPaciente->s_apellido,
                $personaPaciente->nombres,
            ]));

            $datosParaMovimientos = [
                'id_corresponde' => $datosNecesarios['id_corresponde'],
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $datosNecesarios['id_unidad'],
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $nombrePaciente,
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
    }*/

    /*public function registrarMovimientoSolicitudUnidadAFarmacia($datosNecesarios)
    {
        //PARA LA TABLA DE MOVIMIENTOS
        //$movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
        //   ->where('id_unidad', $datosNecesarios['id_unidad'])
        //    ->orderBy('id', 'desc')
        //    ->first();

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
            'id_estado_validacion' => 1,
            'id_medicamento' => $datosNecesarios['id_medicamento'],
            'id_unidad' => $datosNecesarios['id_unidad'],
            'nro_lote' => $datosNecesarios['nro_lote'],
            'id_user_created' => auth()->user()->id,
            'precio_unitario_peps' => $datosNecesarios['precio_unitario_peps'],
            'fecha_vencimiento' => $datosNecesarios['fecha_vencimiento'],
            'stock_real' => false,

        ]);

        //FIN DE LA TABLA DE MOVIMIENTOS
    }*/
}
