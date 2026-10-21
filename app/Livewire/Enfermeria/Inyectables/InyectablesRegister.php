<?php

namespace App\Livewire\Enfermeria\Inyectables;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Enfermeria\ViaParenteral;
use App\Models\Enfermeria\RegistroInyeccion;
use App\Models\Enfermeria\RegistroInyeccionMedicamento;
use App\Models\Enfermeria\RegistroInyeccionProcedimiento; 
use App\Models\Enfermeria\RecetaManual;                   
use App\Services\EnfermeriaNestService;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Models\Farmacia\Medicamento;
use Livewire\Attributes\On;

class InyectablesRegister extends Component
{
    use WithPagination;

    // Modo de receta: 'SISTEMA' o 'MANUAL'
    public string $tipoReceta = 'SISTEMA';

    // --- MODO SISTEMA ---
    public string $nroRecetaBusqueda = '';
    public array $recetaSistemaEncontrada = [];
    public array $medicamentosRecetaSistema = [];

    // --- MODO MANUAL (PACIENTE Y MÉDICO) ---
    public ?int $idPersona = null;
    public string $nombreCompletoPaciente = '';
    public string $matriculaPaciente = '';
    public string $ciPaciente = '';

    public ?int $idEspecialista = null;
    public string $fechaEmisionManual = '';

    // Selector de medicamento en modo manual
    public ?int $idMedicamentoManual = null;
    public int $cantidadManual = 1;
    public ?int $idViaParenteralManual = null;

    // --- BUSCADOR MODAL DE PACIENTES (NestJS) ---
    public string $terminoBusquedaPaciente = '';
    public array $listaPacientesModal = [];

    // --- TABLA TEMPORAL: INYECCIONES INGRESADOS ---
    public array $inyeccionesIngresados = [];

    // --- CHECKLIST DE PROCEDIMIENTOS COMPLEMENTARIOS ---
    public bool $curacion_plana = false;
    public string $obs_curacion_plana = '';
    public bool $curacion_infectada = false;
    public string $obs_curacion_infectada = '';
    public bool $oxigenoterapia = false;
    public string $obs_oxigenoterapia = '';
    public bool $retiro_puntos = false;
    public string $obs_retiro_puntos = '';
    public bool $prueba_sensibilidad = false;
    public string $obs_prueba_sensibilidad = '';
    public bool $sangria = false;
    public string $obs_sangria = '';
    public bool $signos_vitales = false;
    public string $obs_signos_vitales = '';
    public bool $tx_vo = false;
    public string $obs_tx_vo = '';
    public bool $orientacion_salud = false;
    public string $obs_orientacion_salud = '';
    public bool $vendajes = false;
    public string $obs_vendajes = '';
    public bool $nebulizacion = false;
    public string $obs_nebulizacion = '';
    public bool $otros = false;
    public string $obs_otros = '';
    public array $viasParenterales = [];
    public array $especialistas = [];
    public array $medicamentosCatalogo = [];
    // Control del modal
    public bool $modalBuscarPaciente = false;

    // Propiedades del formulario
    public ?int $id_persona = null;
    public string $nombre_paciente = '';
    public string $matricula_paciente = '';
    public string $ci_paciente = '';
    public string $buscarEspecialista = '';
    public string $nombreEspecialistaSeleccionado = '';
    public array $especialistasEncontrados = [];
    public bool $mostrarDropdownEspecialistas = false;
    public string $buscarMedicamento = '';
   
    public string $nombreMedicamentoSeleccionado = '';
    public array $medicamentosFiltrados = [];
    public bool $mostrarDropdownMedicamentos = false;
    

    public function mount()
    {
        $this->fechaEmisionManual = date('Y-m-d');
        $this->cargarMedicamentosInyectables();

        // 1. Cargar las vías parenterales una sola vez
        $this->viasParenterales = ViaParenteral::where('estado', true)
            ->orderBy('nombre')
            ->get()
            ->toArray();

        // 2. Cargar especialistas desde NestJS una sola vez
        $nestService = app(EnfermeriaNestService::class);
        $this->especialistas = $nestService->listarEspecialistasHabilitados() ?? [];

        // 3. Cargar el catálogo de medicamentos una sola vez
        $this->medicamentosCatalogo = Medicamento::where('estado', true)
            ->orderBy('nombre')
            ->limit(100)
            ->get()
            ->toArray();
    }

        // Se dispara cuando la enfermera teclea en el input
    public function updatedBuscarMedicamento(): void
    {
        if (strlen(trim($this->buscarMedicamento)) >= 2) {
            // IDs de medicamentos ya agregados a la tabla para no repetirlos
            $idsYaAgregados = collect($this->inyeccionesIngresados)->pluck('id_medicamento')->toArray();

            $this->medicamentosFiltrados = Medicamento::query()
                ->where('estado', true)
                ->where(function ($q) {
                    $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                      ->orWhere('forma_farmaceutica', 'ILIKE', '%Ampolla%')
                      ->orWhere('forma_farmaceutica', 'ILIKE', '%Frasco%');
                })
                ->where(function ($q) {
                    $term = '%' . trim($this->buscarMedicamento) . '%';
                    $q->where('nombre', 'ILIKE', $term)
                      ->orWhere('codigo_medicamento', 'ILIKE', $term);
                })
                ->whereNotIn('id', $idsYaAgregados)
                ->orderBy('nombre', 'asc')
                ->limit(20)
                ->get()
                ->toArray();

            $this->mostrarDropdownMedicamentos = true;
        } else {
            $this->medicamentosFiltrados = [];
            $this->mostrarDropdownMedicamentos = false;
        }
    }

    public function seleccionarMedicamentoInyectable(int $id, string $codigo, string $nombre): void
    {
        $this->idMedicamentoManual = $id;
        $this->nombreMedicamentoSeleccionado = ($codigo ? "{$codigo} - " : "") . $nombre;
        $this->buscarMedicamento = $this->nombreMedicamentoSeleccionado;
        $this->mostrarDropdownMedicamentos = false;
        $this->medicamentosFiltrados = [];
    }

    public function limpiarMedicamentoInyectable(): void
    {
        $this->idMedicamentoManual = null;
        $this->nombreMedicamentoSeleccionado = '';
        $this->buscarMedicamento = '';
        $this->mostrarDropdownMedicamentos = false;
        $this->medicamentosFiltrados = [];
    }

    public function render()
    {
        // El render() queda totalmente limpio e instantáneo
        return view('livewire.enfermeria.inyectables.inyectables-register');
    }

    public function cargarMedicamentosInyectables()
    {
        // Filtro directo en PostgreSQL de Fase 2:
        $this->medicamentosCatalogo = Medicamento::query()
            ->where('estado', true)
            ->where(function ($q) {
                $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                  ->orWhere('forma_farmaceutica', 'ILIKE', '%Ampolla%')
                  ->orWhere('forma_farmaceutica', 'ILIKE', '%Frasco%');
            })
            ->orderBy('nombre', 'asc')
            ->get()
            ->toArray(); // <-- Convierte la Collection de Eloquent a array nativo
    }
    // Cambiar tipo de receta (SISTEMA / MANUAL)
    public function setTipoReceta(string $tipo)
    {
        $this->tipoReceta = $tipo;
    }

   // --- MODO SISTEMA: BUSCAR RECETA UTILIZANDO TUS MODELOS ELOQUENT ---
    public function buscarRecetaSistema()
    {
        $this->validate([
            'nroRecetaBusqueda' => 'required|string|min:1',
        ], [
            'nroRecetaBusqueda.required' => 'Ingrese el número de receta institucional.',
        ]);

        $termino = trim($this->nroRecetaBusqueda);

        try {
            // 1. Buscar la receta institucional
            $receta = \App\Models\ConsultaExterna\Receta::where(function ($query) use ($termino) {
                    $query->where('nro_receta', $termino)
                          ->orWhere('id', is_numeric($termino) ? (int)$termino : 0);
                })
                ->first();

            if (!$receta) {
                $this->medicamentosRecetaSistema = [];
                $this->recetaSistemaEncontrada = [];
                $this->dispatch('alertaError', mensaje: 'No se encontró ninguna receta con el número ingresado.');
                return;
            }

            $this->recetaSistemaEncontrada = $receta->toArray();
            $this->idPersona = $receta->id_persona;
            $this->idEspecialista = $receta->id_especialista;

            // 2. Obtener paciente con Eloquent (o microservicio de respaldo)
            $persona = \App\Models\Administracion\Persona::find($receta->id_persona);

            if ($persona) {
                $paterno  = $persona->p_apellido ?? '';
                $materno  = $persona->s_apellido ?? '';
                $nombres  = $persona->nombres ?? '';
                $this->nombreCompletoPaciente = trim("{$paterno} {$materno} {$nombres}");
                $this->ciPaciente             = $persona->ci ?? '';
                $this->matriculaPaciente      = $persona->matricula_seguro ?? 'S/M';
            } else {
                try {
                    $token = session('access_token', session('token', ''));
                    $baseUrl = config('services.nestjs.url', 'http://127.0.0.1:3000/api');
                    $res = \Illuminate\Support\Facades\Http::withToken($token)
                        ->timeout(3)
                        ->get("{$baseUrl}/pacientes/{$receta->id_persona}");

                    if ($res->successful() && !empty($res->json())) {
                        $dataP = $res->json();
                        $this->nombreCompletoPaciente = $dataP['nombreCompleto'] ?? $dataP['nombre_completo'] ?? "ASEGURADO #{$receta->id_persona}";
                        $this->ciPaciente             = $dataP['ci'] ?? '';
                        $this->matriculaPaciente      = $dataP['matricula'] ?? 'S/M';
                    } else {
                        $this->nombreCompletoPaciente = "ASEGURADO SSU #{$receta->id_persona}";
                        $this->matriculaPaciente      = 'S/M';
                        $this->ciPaciente             = '';
                    }
                } catch (\Throwable $e) {
                    $this->nombreCompletoPaciente = "ASEGURADO SSU #{$receta->id_persona}";
                    $this->matriculaPaciente      = 'S/M';
                    $this->ciPaciente             = '';
                }
            }

            // 3. Consultar los detalles de medicamentos de la receta
            $detalles = \App\Models\ConsultaExterna\RecetaMedicamento::where('id_receta', $receta->id)->get();

            if ($detalles->isEmpty()) {
                $this->medicamentosRecetaSistema = [];
                $this->dispatch('alertaError', mensaje: 'La receta existe pero no tiene medicamentos prescritos.');
                return;
            }

            // 4. Mapear filtrando ÚNICAMENTE los medicamentos cuya forma farmacéutica sea "Inyectable"
            $inyectables = [];
            foreach ($detalles as $det) {
                $med = \App\Models\Farmacia\Medicamento::where('id', $det->id_medicamento)
                    ->where(function($q) {
                        $q->where('forma_farmaceutica', 'ILIKE', '%Inyectable%')
                          ->orWhere('nombre', 'ILIKE', '%Inyectable%');
                    })
                    ->first();

                // Si no es inyectable (ej. comprimidos, jarabes, etc.), se omite
                if (!$med) {
                    continue;
                }

                $inyectables[] = [
                    'id_detalle'        => $det->id,
                    'id_medicamento'    => $det->id_medicamento,
                    'codigo'            => $med->codigo_medicamento ?? '',
                    'nombre'            => $med->nombre ?? 'Medicamento Inyectable',
                    'cantidad'          => $det->cantidad ?? 1,
                    'via_sugerida'      => $det->forma_adm ?? 'Intramuscular',
                    'id_via_parenteral' => 1, // Por defecto Intramuscular
                ];
            }

            if (empty($inyectables)) {
                $this->medicamentosRecetaSistema = [];
                $this->dispatch('alertaError', mensaje: 'La receta no contiene medicamentos de tipo inyectable.');
                return;
            }

            $this->medicamentosRecetaSistema = $inyectables;
            $this->dispatch('alertaExito', mensaje: 'Receta cargada con éxito. Se muestran solo inyectables.');

        } catch (\Throwable $th) {
            $this->dispatch('alertaError', mensaje: 'Error al buscar receta: ' . $th->getMessage());
        }
    }

   // Se ejecuta automáticamente cuando la enfermera teclea en el buscador
    public function updatedBuscarEspecialista(): void
    {
        if (strlen(trim($this->buscarEspecialista)) >= 2) {
            $service = app(\App\Services\EnfermeriaNestService::class);
            $this->especialistasEncontrados = $service->obtenerEspecialistas($this->buscarEspecialista, 15);
            $this->mostrarDropdownEspecialistas = true;
        } else {
            $this->especialistasEncontrados = [];
            $this->mostrarDropdownEspecialistas = false;
        }
    }

    public function seleccionarEspecialista(int $id, string $nombre, string $especialidad = ''): void
    {
        $this->idEspecialista = $id;
        $this->nombreEspecialistaSeleccionado = $nombre . ($especialidad ? " [{$especialidad}]" : "");
        $this->buscarEspecialista = $this->nombreEspecialistaSeleccionado;
        $this->mostrarDropdownEspecialistas = false;
        $this->especialistasEncontrados = [];
    }

    public function limpiarEspecialista(): void
    {
        $this->idEspecialista = null;
        $this->nombreEspecialistaSeleccionado = '';
        $this->buscarEspecialista = '';
        $this->mostrarDropdownEspecialistas = false;
        $this->especialistasEncontrados = [];
    }
    // Agregar medicamento desde la tabla superior de receta sistema
    public function agregarInyeccionSistema(int $index)
    {
        if (!isset($this->medicamentosRecetaSistema[$index])) {
            return;
        }

        $item = $this->medicamentosRecetaSistema[$index];

        // 1. Obtener nombre legible de la vía parenteral
        $nombreVia = 'Intramuscular';
        foreach ($this->viasParenterales as $via) {
            $vId = is_array($via) ? $via['id'] : $via->id;
            if ($vId == $item['id_via_parenteral']) {
                $nombreVia = is_array($via) ? $via['nombre'] : $via->nombre;
                break;
            }
        }

        // 2. Extraer de forma estricta el ID numérico de la receta actual
        $idRecetaActual = $this->recetaSistemaEncontrada['id'] 
                       ?? $this->recetaSistemaEncontrada['id_receta'] 
                       ?? null;

        // 3. Agregar a la tabla acumulada inferior con todos los datos necesarios
        $this->inyeccionesIngresados[] = [
            'nro_receta'            => (string) $this->nroRecetaBusqueda,
            'id_receta'             => $idRecetaActual ? (int) $idRecetaActual : null,
            'id_receta_manual'      => null,
            'id_medicamento'        => (int) $item['id_medicamento'],
            'medicamento_nombre'    => $item['nombre'],
            'codigo_medicamento'    => $item['codigo'] ?? '',
            'id_via_parenteral'     => (int) $item['id_via_parenteral'],
            'via_parenteral_nombre' => $nombreVia,
            'cantidad'              => (int) ($item['cantidad'] ?? 1),
            'id_persona'            => (int) $this->idPersona,
            'id_especialista'       => !empty($this->idEspecialista) ? (int) $this->idEspecialista : null,
            'item_receta_original'  => $item,
        ];

        // 4. Remover el medicamento seleccionado de la tabla superior
        unset($this->medicamentosRecetaSistema[$index]);
        $this->medicamentosRecetaSistema = array_values($this->medicamentosRecetaSistema);

        $this->dispatch('alertaExito', mensaje: 'Inyección agregada a la lista.');
    }

    // --- GUARDADO TRANSACCIONAL ATÓMICO (AGRUPADO POR RECETA) ---
   // --- GUARDADO TRANSACCIONAL ATÓMICO (AGRUPADO POR RECETA) ---
    public function guardarAtencion()
    {
        // 1. Validaciones previas
        if (empty($this->inyeccionesIngresados)) {
            $this->dispatch('alertaError', mensaje: 'Debe ingresar al menos una inyección en la lista.');
            return;
        }

        if (empty($this->idPersona) && !empty($this->inyeccionesIngresados[0]['id_persona'])) {
            $this->idPersona = (int) $this->inyeccionesIngresados[0]['id_persona'];
        }

        if (empty($this->idPersona)) {
            $this->dispatch('alertaError', mensaje: 'No se ha identificado al paciente.');
            return;
        }

        try {
            DB::transaction(function () {
                $idUsuario = auth()->id() ?? session('user_id', 1);

                // 2. Agrupar medicamentos por procedencia
                $gruposPorReceta = [];
                foreach ($this->inyeccionesIngresados as $item) {
                    $idRec = $item['id_receta'] ?? null;
                    $clave = $idRec ? "SIS_{$idRec}" : "MANUAL";
                    $gruposPorReceta[$clave][] = $item;
                }

                $esPrimerRegistro = true;

                // 3. Iterar cada grupo de prescripción
                foreach ($gruposPorReceta as $clave => $itemsDelGrupo) {
                    $primerItem = $itemsDelGrupo[0];
                    $idRecetaActual = $primerItem['id_receta'] ?? null;
                    $idRecetaManualActual = null;

                    // SI ES RECETA MANUAL: INSERTAR EN enfermeria.receta_manual
                    if (empty($idRecetaActual)) {
                        $especialistaId = $primerItem['id_especialista'] ?? $this->idEspecialista ?? null;
                        $fechaManual     = $primerItem['fecha_receta'] ?? $this->fechaEmisionManual ?? now()->toDateString();
                        $primerMedId     = $primerItem['id_medicamento'] ?? null;

                        $idRecetaManualActual = DB::table('enfermeria.receta_manual')->insertGetId([
                            'id_persona'      => (int) $this->idPersona,
                            'id_especialista' => $especialistaId ? (int) $especialistaId : 1,
                            'id_medicamento'  => (int) $primerMedId,
                            'fecha_receta'    => $fechaManual,
                            'estado'          => true,
                            'id_user_created' => (int) $idUsuario,
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]);
                    }

                    // A. Insertar Cabecera: enfermeria.registro_inyeccion
                    $idViaGeneral = $primerItem['id_via_parenteral'] ?? 1;
                    $espId = $primerItem['id_especialista'] ?? $this->idEspecialista ?? null;

                    $idRegistroInyeccion = DB::table('enfermeria.registro_inyeccion')->insertGetId([
                        'id_persona'        => (int) $this->idPersona,
                        'id_especialista'   => $espId ? (int) $espId : null,
                        'id_via_parenteral' => (int) $idViaGeneral,
                        'id_receta'         => $idRecetaActual ? (int) $idRecetaActual : null,
                        'id_receta_manual'  => $idRecetaManualActual, // ID generado en receta_manual
                        'estado'            => true,
                        'id_user_created'   => (int) $idUsuario,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);

                    // B. Insertar cada fármaco en enfermeria.registro_inyeccion_medicamento
                    foreach ($itemsDelGrupo as $med) {
                        $obs = "Vía: " . ($med['via_parenteral_nombre'] ?? 'General') . " | Receta: " . ($med['nro_receta'] ?? 'S/N');
                        if (!empty($med['observacion'])) {
                            $obs .= " | " . $med['observacion'];
                        }

                        DB::table('enfermeria.registro_inyeccion_medicamento')->insert([
                            'id_registro_inyeccion' => $idRegistroInyeccion,
                            'id_medicamento'        => (int) $med['id_medicamento'],
                            'observacion'           => $obs,
                            'estado'                => true,
                            'id_user_created'       => (int) $idUsuario,
                            'created_at'            => now(),
                            'updated_at'            => now(),
                        ]);
                    }

                    // C. Registrar Procedimientos Complementarios en la cabecera principal
                    if ($esPrimerRegistro) {
                        DB::table('enfermeria.registro_inyeccion_procedimiento')->insert([
                            'id_registro_inyeccion' => $idRegistroInyeccion,
                            'curacion_plana'        => (bool) $this->curacion_plana,
                            'curacion_infectada'    => (bool) $this->curacion_infectada,
                            'oxigenoterapia'        => (bool) $this->oxigenoterapia,
                            'retiro_puntos'         => (bool) $this->retiro_puntos,
                            'prueba_sensibilidad'   => (bool) $this->prueba_sensibilidad,
                            'sangria'               => (bool) $this->sangria,
                            'signos_vitales'        => (bool) $this->signos_vitales,
                            'tx_vo'                 => (bool) $this->tx_vo,
                            'orientacion_salud'     => (bool) $this->orientacion_salud,
                            'vendajes'              => (bool) $this->vendajes,
                            'nebulizacion'          => (bool) $this->nebulizacion,
                            'otros'                 => (bool) $this->otros,
                            'observaciones'         => $this->construirObservacionesProcedimientos(),
                            'estado'                => true,
                            'id_user_created'       => (int) $idUsuario,
                            'created_at'            => now(),
                            'updated_at'            => now(),
                        ]);
                        $esPrimerRegistro = false;
                    }
                }
            });

            // Limpieza del estado y redirección
            session()->flash('success', 'Atención de enfermería registrada con éxito.');
            return redirect()->route('enfermeria.inyectables.index');

        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error('Error al registrar inyección y receta manual: ' . $th->getMessage());
            $this->dispatch('alertaError', mensaje: 'Error al registrar: ' . $th->getMessage());
        }
    }

  // Método que llama tu botón: wire:click="abrirModalPacientes"

    public function cerrarModalPacientes(): void
    {
        $this->modalBuscarPaciente = false;
    }

    


    // --- MODO MANUAL: MODAL DE PACIENTES (NestJS) ---
    public function abrirModalPacientes()
    {
        $this->modalBuscarPaciente = true;
        $this->terminoBusquedaPaciente = '';
        $this->ejecutarBusquedaPacientesModal();
        $this->dispatch('abrirModalAsegurados');
    }

    public function updatedTerminoBusquedaPaciente()
    {
        $this->ejecutarBusquedaPacientesModal();
    }

    public function ejecutarBusquedaPacientesModal()
    {
        $nestService = app(EnfermeriaNestService::class);
        $res = $nestService->buscarPacientes($this->terminoBusquedaPaciente, 1, 20);
        $this->listaPacientesModal = $res['data'] ?? $res ?? [];
    }

    public function seleccionarPacienteModal(int $idPersona, string $nombreCompleto, string $ci, string $matricula)
    {
        $this->idPersona = $idPersona;
        $this->nombreCompletoPaciente = $nombreCompleto;
        $this->ciPaciente = $ci;
        $this->matriculaPaciente = $matricula;

        $this->dispatch('cerrarModalAsegurados');
    }

    // Agregar medicamento en modo manual
    public function agregarInyeccionManual(): void
    {
        // Validaciones
        if (empty($this->idPersona)) {
            $this->dispatch('alertaError', mensaje: 'Debe seleccionar un paciente.');
            return;
        }

        if (empty($this->idMedicamentoManual)) {
            $this->dispatch('alertaError', mensaje: 'Debe seleccionar un medicamento inyectable.');
            return;
        }

        if (empty($this->idViaParenteralManual)) {
            $this->dispatch('alertaError', mensaje: 'Debe seleccionar una vía parenteral.');
            return;
        }

        // Obtener nombres para la visualización en la tabla
        $med = \App\Models\Farmacia\Medicamento::find($this->idMedicamentoManual);
        $via = \App\Models\Enfermeria\ViaParenteral::find($this->idViaParenteralManual);

        $nombreMed = $med ? "{$med->codigo_medicamento} - {$med->nombre}" : "Medicamento #{$this->idMedicamentoManual}";
        $nombreVia = $via ? $via->nombre : 'Vía General';

        // Agregar al arreglo temporal en memoria
        $this->inyeccionesIngresados[] = [
            'tipo'                  => 'MANUAL',
            'nro_receta'            => 'S/N',
            'id_receta'             => null,
            'id_receta_manual'      => null, // Se generará en la transacción
            'id_persona'            => $this->idPersona,
            'id_especialista'       => $this->idEspecialista ?: null,
            'fecha_receta'          => $this->fechaEmisionManual ?: now()->toDateString(),
            'id_medicamento'        => $this->idMedicamentoManual,
            'medicamento_nombre'    => $nombreMed,
            'id_via_parenteral'     => $this->idViaParenteralManual,
            'via_parenteral_nombre' => $nombreVia,
            'cantidad'              => $this->cantidadManual ?: 1,
            'observacion'           => "Receta manual emitida el " . ($this->fechaEmisionManual ?: now()->toDateString()),
        ];

        // Limpiar el selector de medicamento y cantidad
        $this->limpiarMedicamentoInyectable();
        $this->cantidadManual = 1;
    }

      // Quitar inyección de la lista temporal
    public function quitarInyeccion(int $index)
    {
        if (isset($this->inyeccionesIngresados[$index])) {
            $eliminado = $this->inyeccionesIngresados[$index];

            // 🟢 Si provenía de la receta del sistema activa, se restaura en la tabla superior
            if (!empty($eliminado['item_receta_original']) && $eliminado['nro_receta'] == $this->nroRecetaBusqueda) {
                $this->medicamentosRecetaSistema[] = $eliminado['item_receta_original'];
            }

            unset($this->inyeccionesIngresados[$index]);
            $this->inyeccionesIngresados = array_values($this->inyeccionesIngresados);
        }
    }


    // Método para abrir el modal desde el botón "BUSCAR PACIENTE"
    public function abrirModalPaciente(): void
    {
        $this->modalBuscarPaciente = true;
    }

    public function cerrarModalPaciente(): void
    {
        $this->modalBuscarPaciente = false;
    }

    // Escuchador que captura la selección realizada en PowerGrid
   #[On('pacienteSeleccionado')]
    public function pacienteSeleccionado($id = null, $nombre_completo = '', $matricula = 'S/M', $ci = 'S/C', $datos = null): void
    {
        // Si viene empaquetado en $id o $datos por compatibilidad
        if (is_array($id)) {
            $nombre_completo = $id['nombre_completo'] ?? $nombre_completo;
            $matricula       = $id['matricula'] ?? $matricula;
            $ci              = $id['ci'] ?? $ci;
            $id              = $id['id'] ?? null;
        } elseif (is_array($datos)) {
            $id              = $datos['id'] ?? $id;
            $nombre_completo = $datos['nombre_completo'] ?? $nombre_completo;
            $matricula       = $datos['matricula'] ?? $matricula;
            $ci              = $datos['ci'] ?? $ci;
        }

        // Asignación a las propiedades del formulario
        $this->idPersona              = $id;
        $this->nombreCompletoPaciente = $nombre_completo;
        $this->matriculaPaciente      = !empty($matricula) ? $matricula : 'S/M';
        $this->ciPaciente             = !empty($ci) ? $ci : 'S/C';

        // Cerrar el modal automáticamente
        $this->modalBuscarPaciente = false;
    }
   

    /**
     * Concatena las observaciones del checklist de procedimientos
     */
    private function construirObservacionesProcedimientos(): string
    {
        $obs = [];
        if ($this->curacion_plana && !empty($this->obs_curacion_plana)) $obs[] = "Curación plana: {$this->obs_curacion_plana}";
        if ($this->curacion_infectada && !empty($this->obs_curacion_infectada)) $obs[] = "Curación infectada: {$this->obs_curacion_infectada}";
        if ($this->oxigenoterapia && !empty($this->obs_oxigenoterapia)) $obs[] = "Oxigenoterapia: {$this->obs_oxigenoterapia}";
        if ($this->retiro_puntos && !empty($this->obs_retiro_puntos)) $obs[] = "Retiro puntos: {$this->obs_retiro_puntos}";
        if ($this->prueba_sensibilidad && !empty($this->obs_prueba_sensibilidad)) $obs[] = "Prueba sensibilidad: {$this->obs_prueba_sensibilidad}";
        if ($this->sangria && !empty($this->obs_sangria)) $obs[] = "Sangría: {$this->obs_sangria}";
        if ($this->signos_vitales && !empty($this->obs_signos_vitales)) $obs[] = "Signos vitales: {$this->obs_signos_vitales}";
        if ($this->tx_vo && !empty($this->obs_tx_vo)) $obs[] = "Tx VO: {$this->obs_tx_vo}";
        if ($this->orientacion_salud && !empty($this->obs_orientacion_salud)) $obs[] = "Orientación salud: {$this->obs_orientacion_salud}";
        if ($this->vendajes && !empty($this->obs_vendajes)) $obs[] = "Vendajes: {$this->obs_vendajes}";
        if ($this->nebulizacion && !empty($this->obs_nebulizacion)) $obs[] = "Nebulización: {$this->obs_nebulizacion}";
        if ($this->otros && !empty($this->obs_otros)) $obs[] = "Otros: {$this->obs_otros}";

        return implode(' | ', $obs);
    }

    public function cancelar()
    {
        return redirect()->route('enfermeria.inyectables.index')->with('evento', 'InyeccionCancelada');
    }
}