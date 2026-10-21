<?php

namespace App\Livewire\AtencionMedica\OtrasConsultas;

use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\BajaMedica;
use App\Models\ConsultaExterna\Fisioterapia;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Imagenologia;
use App\Models\ConsultaExterna\Internacion;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\Referencia;
use App\Models\Laboratorio\Solicitudes;
use App\Services\EspecialidadService;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class OtrasConsultasMain extends Component
{
    public $datosPaciente, $asignaciones_horario;
    public $id_historia_clinica, $listaEspecialistas;
    public $id_tipo_consulta, $id_persona, $id_especialidad, $asistencia,
        $fecha_hora, $id_especialista, $motivo_consulta, $enfermedad_actual, $objetivo,
        $diagnostico, $descriptivo, $diagnosticosCie10 = [], $id_user_created, $created_at,
        $nombreEspecialista, $nombreEspecialidad;
    public $datos_paciente, $edad;

    public $recetas = [], $laboratorios = [], $imagenologias = [],
        $bajas_medicas = [], $fisioterapias = [], $internaciones = [], $referencias = [];

    public function mount()
    {
        $especialidadService = new EspecialidadService();
        $this->listaEspecialistas = $especialidadService->obtenerEspecialistas();
    }
    public function render()
    {
        return view('livewire.atencion-medica.otras-consultas.otras-consultas-main');
    }

    public function registrarConsulta()
    {
        $this->dispatch('modalSelectPacienteConsulta');
        //return redirect()->route('atencion_medica.otras_consultas.create');
    }

    #[On('registrarPacienteSelect')]
    public function registrarPacienteSelect($data)
    {
        $this->datosPaciente = $data;
        session(['agenda_data' => $data]);
        return redirect()->route('atencion_medica.otras_consultas.create');
    }

    #[On('ver_historia_clinica')]
    public function ver_historia_clinica($rowId)
    {
        $this->id_historia_clinica = $rowId;
        $this->mostrarDatosHistoriaClinica($this->id_historia_clinica);
        $this->dispatch('modalMostrarHistoriaClinica');
    }

    public function mostrarDatosHistoriaClinica($id)
    {
        try {
            $historia = HistoriaClinica::find($id);
            $this->datos_paciente = Persona::find($historia->id_persona);
            $fechaNacimiento = $this->datos_paciente?->fecha_nacimiento ? \Carbon\Carbon::parse($this->datos_paciente->fecha_nacimiento) : null;
            $this->edad = $fechaNacimiento?->age;
            //dd($this->listaEspecialistas[0]);
            if ($historia) {
                $this->id_tipo_consulta = $historia->historiaClinicaTipoConsulta->nombre;
                $this->id_persona = $historia->HistoriaClinicaPersona->nombres . ' ' . $historia->HistoriaClinicaPersona->p_apellido
                    . ' ' . $historia->HistoriaClinicaPersona->s_apellido;
                $this->id_especialidad = $historia->id_especialidad;
                $this->asistencia = $historia->asistencia ? 'Asistió' : 'No asistió';
                $this->fecha_hora = $historia->fecha_hora;
                $this->id_especialista = $historia->id_especialista;
                //soap
                $this->motivo_consulta = $historia->motivo_consulta;
                $this->enfermedad_actual = $historia->enfermedad_actual;
                $this->objetivo = $historia->objetivo;
                $this->diagnostico = $historia->diagnostico ?? '';
                $this->descriptivo = $historia->descriptivo;
                $this->diagnosticosCie10 = $historia->HistoriaClinicaCie10 ?? [];
                $this->id_user_created = $historia->id_user_created;
                $this->created_at = $historia->created_at;

                $especialista = collect($this->listaEspecialistas)
                    ->firstWhere('id', $historia->id_especialista);

                if ($especialista) {
                    $this->nombreEspecialista = $especialista['persona_especialista']['nombres'] . ' ' .
                        $especialista['persona_especialista']['p_apellido'] . ' ' .
                        $especialista['persona_especialista']['s_apellido'];
                } else {
                    $this->nombreEspecialista = 'No encontrado';
                }

                $especialidad = collect($this->listaEspecialistas)
                    ->firstWhere('id_especialidad', $historia->id_especialidad);

                if ($especialidad) {
                    $this->nombreEspecialidad = $especialidad['especialidad_especialista']['especialidad'];
                } else {
                    $this->nombreEspecialidad = 'No encontrado';
                }

                //dd($this->recetas, $this->laboratorios, $this->imagenologias,
                //   $this->bajas_medicas, $this->fisioterapias, $this->internaciones, $this->referencias);
            }
        } catch (Exception $e) {
            dd($e);
            Log::error('Error al crear Solicitud', [
                'excepcion' => class_basename($e),
                'detalle'   => $e->getMessage(),
                'codigo'      => $e->getCode(),
                'archivo'      => $e->getFile(),
                'linea'      => $e->getLine(),
                'user_id'   => optional(auth()->user())->id,
                'ip'        => request()->ip(),
            ]);
        }
    }

    public function mostrarModalReceta()
    {
        //dd($this->id_historia_clinica);
        $this->recetas = [];
        $listaRecetas = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 1)
            ->get();
        foreach ($listaRecetas as $plan) {
            $this->recetas[] = Receta::with('medicamentosReceta')->find($plan->id_corresponde);
        }
        //dd($this->id_historia_clinica,$listaRecetas,$this->recetas);
        $this->dispatch('modalMostrarReceta');
        //$this->skipRender();
    }
    public function mostrarModalLaboratorio()
    {
        $this->laboratorios = [];
        $listaLaboratorios = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 2)
            ->get();
        foreach ($listaLaboratorios as $plan) {
            $this->laboratorios[] = Solicitudes::find($plan->id_corresponde); //revisar Laboratorio
        }
        $this->dispatch('modalMostrarLaboratorio');
        //dd('dentra mostrarModalLaboratorio', $listaLaboratorios);
    }
    public function mostrarModalImagenologia()
    {
        $this->imagenologias = [];
        $listaImagenologias = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 3)
            ->get();
        foreach ($listaImagenologias as $plan) {
            $this->imagenologias[] = Imagenologia::with('imagenologiaEstudio')->find($plan->id_corresponde);
        }
        $this->dispatch('modalMostrarImagenologia');
        //dd('dentra mostrarModalImagenologia', $listaImagenologias,$this->imagenologias);
    }
    public function mostrarModalBajaMedica()
    {
        $this->bajas_medicas = [];
        $listaBajaMedica = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 4)
            ->get();
        foreach ($listaBajaMedica as $plan) {
            $this->bajas_medicas[] = BajaMedica::find($plan->id_corresponde);
        }
        $this->dispatch('modalMostrarBajaMedica');
    }
    public function mostrarModalFisioterapia()
    {
        $this->fisioterapias = [];
        $listaFisioterapia = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 5)
            ->get();
        foreach ($listaFisioterapia as $plan) {
            $this->fisioterapias[] = Fisioterapia::find($plan->id_corresponde);
        }
        //dd($listaFisioterapia,$this->fisioterapias);
        $this->dispatch('modalMostrarFisioterapia');
    }
    public function mostrarModalInternacion()
    {
        $this->internaciones = [];
        $listaInternacion = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 6)
            ->get();
        foreach ($listaInternacion as $plan) {
            $this->internaciones[] = Internacion::find($plan->id_corresponde);
        }
        $this->dispatch('modalMostrarInternacion');
        //dd('dentra mostrarModalInternacion', $listaInternacion,$this->internaciones);
    }
    public function mostrarModalReferencia()
    {
        $this->referencias = [];
        $listaReferencia = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 7)
            ->get();
        foreach ($listaReferencia as $plan) {
            $referencia = Referencia::find($plan->id_corresponde);
            $this->referencias[] = $referencia;
            $especialidad = collect($this->listaEspecialistas)
                ->firstWhere('id_especialidad', $referencia->id_especialidad);
            if ($especialidad) {
                $referencia->nombre_especialidad = $especialidad['especialidad_especialista']['especialidad'] ?? 'No definida';
            }
        }
        //dd($this->listaEspecialistas, $listaReferencia,$this->referencias);
        $this->dispatch('modalMostrarReferencia');
    }

    #[On('abrir_copia_original')]
    public function abrir_copia_original($rowId)
    {
        // dd('dsfsd');
        $this->js('window.open("/consulta_externa/pdf/copia_recuperada/' . $rowId . '", "_blank")');
    }

    #[On('abrir_copia_historia')]
    public function abrir_copia_historia($rowId)
    {
        //  dd($rowId);
        $this->js('window.open("/consulta_externa/pdf/c_e_copia/' . $rowId . '", "_blank")');
    }
}
