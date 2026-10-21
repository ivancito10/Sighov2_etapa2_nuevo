<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\FormAtencionMedica;

use App\Models\ConsultaExterna\DetalleFormulario;
use App\Models\ConsultaExterna\EspecialidadVariable;
use App\Models\ConsultaExterna\Formulario;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Variable;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class FormAtencionMedicaMain extends Component
{   
    public $datos_agenda,$variables = [],$valores = [];
    public $nombre, $edad, $genero, $tipo_asegurado, $matricula_seguro;
    public $id_agenda, $id_consulta_externa, $id_formulario;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.form-atencion-medica.form-atencion-medica-main');
    }
    public function mount(){
        $this->datos_agenda = session('agenda_data','');
        //dd($datos);
        $this->mostrarDatosPaciente($this->datos_agenda);
        $this->obtenerVariables($this->datos_agenda);
    }

    public function mostrarDatosPaciente($datos){
        $this->nombre = $datos['nombre_persona'];
        $this->edad = $datos['edad'];
        $this->genero = $datos['genero'];
        $this->tipo_asegurado = $datos['tipo_asegurado'];
        $this->matricula_seguro = $datos['matricula_seguro'];
    }

    public function obtenerVariables($datos){
        $especialidadId = $datos['asignacion_horario_agenda']['especialista_asignacion_horario']['id_especialidad'];
        $this->id_agenda = $datos['id'] ?? $this->id_agenda;
        //dd($datos);
        $this->variables = EspecialidadVariable::query()
            ->with([
                'especialidadVariableVariable',
                'especialidadVariableDetalles' => function ($q) {
                    $q->when($this->id_agenda,fn($qq) => $qq->where('id_agenda', $this->id_agenda));
                }
            ])
            ->where('id_especialidad', $especialidadId)
            ->get();

        $this->valores = []; 
        foreach ($this->variables as $ev) {
            $detalle = $ev->especialidadVariableDetalles->first(); // por el filtro debería haber 0 o 1
            $valor   = $detalle?->valor;

            // Casting opcional según tipo (útil para checkbox bool)
            $tipo = $ev->especialidadVariableVariable->tipo ?? null;
            if ($tipo === 'integer') {
                $valor = is_null($valor) ? null : (int) $valor;
            } elseif ($tipo === 'decimal' || $tipo === 'numeric') {
                $valor = is_null($valor) ? null : (float) $valor;
            } elseif ($tipo === 'boolean' || $tipo === 'boolean') {
                $valor = (bool) ($valor ?? false);
            }
            $this->valores[$ev->id] = $valor;
        }
    }

    #[On('guardarVariables')]
    public function guardarVariables(){
        try{
            //dd($this->valores);
            DB::beginTransaction();
            //crear formulario de atencion medica (por siacaso xd)
            $fecha_actual = now()->toDateString(); 
            
            $formularioExiste = Formulario::whereDate('created_at', $fecha_actual)
                ->where('id_asignacion_horario', $this->datos_agenda['id_asignacion_horario'])
                ->lockForUpdate() // evita condición d0e carrera dentro de la transacción
                ->first();

            if (!$formularioExiste) {
                $datos_formulario = [
                    'id_persona_enfermera' => null,
                    'id_asignacion_horario' => $this->datos_agenda['id_asignacion_horario'],
                    'id_user_created' => auth()->user()->id,
                    'id_user_updated' => auth()->user()->id,
                    'created_at' => now(),
                ];

                $formularioExiste = Formulario::create($datos_formulario);
            }

            $historia_clinica = HistoriaClinica::where('id_agenda',$this->datos_agenda['id'])
                ->where('id_persona',$this->datos_agenda['id_persona'])
                ->first();
            //guardar variables
            foreach ($this->valores as $idVariable => $valor) {
                if ($valor === null || $valor === '') continue;
                $datosFormulario = [
                    'id_especialidad_variable' => $idVariable, 
                    'valor' => $valor,
                    'id_agenda' => $this->datos_agenda['id'] ,
                    'id_formulario' => $formularioExiste->id,
                    'id_consulta_externa' => $historia_clinica->id,
                    'created_at'=>now(),
                    'id_user_created' => auth()->user()->id,
                    'id_user_updated' => auth()->user()->id,
                ];
                //dd($datosFormulario);
                DetalleFormulario::updateOrCreate([ // 🔍 Criterios de búsqueda (clave única)
                    'id_especialidad_variable' => $idVariable,
                    'id_agenda'                => $this->datos_agenda['id'],
                    'id_formulario'            => $formularioExiste->id,
                    'id_consulta_externa'      => $historia_clinica->id,
                            ],
                $datosFormulario);
            }
            DB::commit();
            $this->dispatch('variablesGuardadas');
            return redirect()->route('atencion_medica.consulta_externa.index');
        }catch (Exception $e) {
            DB::rollBack();
            dd($e);
            Log::error('Error al guardar el formulario de atencion: ' . $e->getMessage());
            $this->dispatch('error');
        }

    }

    public function cancelar(){
        return redirect()->route('atencion_medica.consulta_externa.index');
    }
}
