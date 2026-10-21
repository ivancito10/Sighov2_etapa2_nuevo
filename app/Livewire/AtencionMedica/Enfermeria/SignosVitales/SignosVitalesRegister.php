<?php

namespace App\Livewire\AtencionMedica\Enfermeria\SignosVitales;

use App\Models\Enfermeria\SignoVital;
use App\Models\Plataforma\Agenda;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class SignosVitalesRegister extends Component
{
    public $idAgenda;
    public $agenda;
    public $nombreCompleto;
    public $matricula;
    public $ci;
    public $especialista;
    public $especialidad;
    public $fechaAgenda;
    public $horaAgenda;

    public $presionArterial;
    public $frecuenciaRespiratoria;
    public $temperatura;
    public $frecuenciaCardiaca;
    public $saturacionOxigeno;
    public $peso;
    public $talla;
    public $idEspecialista;
    public $idEspecialidad;


    public function render()
    {
        return view('livewire.atencion-medica.enfermeria.signos-vitales.signos-vitales-register');
    }

    public function rules()
    {
        return [
            'presionArterial' => 'required|string',
            'frecuenciaRespiratoria' => 'required|integer',
            'temperatura' => 'required|numeric',
            'frecuenciaCardiaca' => 'required|integer',
            'saturacionOxigeno' => 'required|integer',
            'peso' => 'nullable|numeric',
            'talla' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'presionArterial.required' => 'La presión arterial es obligatoria.',
            'frecuenciaRespiratoria.required' => 'La frecuencia respiratoria es obligatoria.',
            'temperatura.required' => 'La temperatura es obligatoria.',
            'frecuenciaCardiaca.required' => 'La frecuencia cardiaca es obligatoria.',
            'saturacionOxigeno.required' => 'La saturación de oxígeno es obligatoria.',
        ];
    }


    #[On('abrirModalRegister')]
    public function abrirModalRegister($data)
    {
        $this->resetearValores();
        $this->idAgenda = $data;
        $this->mostrarDatosAgenda();
        $this->dispatch('modalRegistrarSignosVitales');
    }

    public function mostrarDatosAgenda()
    {
        $this->agenda = Agenda::query()
            ->with([
                'personaAgenda',
                'asignacionHorarioAgenda.especialistaDetalles.persona',
                'asignacionHorarioAgenda.especialistaDetalles.especialidadEspecialista'
            ])
            ->find($this->idAgenda);

        $persona = $this->agenda->personaAgenda;
        $this->nombreCompleto = trim(($persona->p_apellido ?? '') . ' ' . ($persona->s_apellido ?? '') . ' ' . ($persona->nombres ?? ''));
        $this->matricula = $persona->matricula_seguro ?? 'SIN MATRÍCULA';
        $this->ci = $persona->ci  ?? 'SIN CI';

        //dd($this->nombre_completo);
        $especialista = optional($this->agenda->asignacionHorarioAgenda)->especialistaDetalles;
        $personaEspecialista = optional($especialista)->persona;

        $this->especialista = trim(($personaEspecialista->p_apellido ?? '') . ' ' . ($personaEspecialista->s_apellido ?? '') . ' ' . ($personaEspecialista->nombres ?? ''));
        $this->especialidad =  optional($especialista->especialidadEspecialista)->especialidad ?? 'SIN ESPECIALIDAD';

        $this->fechaAgenda = $this->agenda->fecha_agenda ? Carbon::parse($this->agenda->fecha_agenda)->format('d/m/Y') : 'SIN FECHA';
        $this->horaAgenda = $this->agenda->hora_agenda ?? '';

        $this->idEspecialista = $this->agenda->asignacionHorarioAgenda->especialistaDetalles->id;
        $this->idEspecialidad = $this->agenda->asignacionHorarioAgenda->especialistaDetalles->id;

        //Para mostrar signos vitales

        if ($this->idAgenda) {
            $signosVitales = SignoVital::where('id_agenda', $this->idAgenda)->first();
            if ($signosVitales) {
                $this->presionArterial = $signosVitales->presion_arterial;
                $this->frecuenciaRespiratoria = $signosVitales->frecuencia_respiratoria;
                $this->temperatura = $signosVitales->temperatura;
                $this->frecuenciaCardiaca = $signosVitales->frecuencia_cardiaca;
                $this->saturacionOxigeno = $signosVitales->saturacion_oxigeno;
                $this->peso = $signosVitales->peso;
                $this->talla = $signosVitales->talla;
            } else {
                $this->presionArterial = '';
                $this->frecuenciaRespiratoria = '';
                $this->temperatura = '';
                $this->frecuenciaCardiaca = '';
                $this->saturacionOxigeno = '';
                $this->peso = '';
                $this->talla = '';
            }
        }
    }

    #[On('cancelarRegistro')]
    public function cancelarRegistro()
    {
        $this->resetearValores();
    }

    public function preguntarGuardarSignosVitales()
    {
        //dd("LLega");
        $this->validate();
        $this->dispatch('preguntaGuardarSignos');
    }

    public function resetearValores()
    {
        $this->reset(['presionArterial', 'frecuenciaRespiratoria', 'temperatura', 'frecuenciaCardiaca', 'saturacionOxigeno', 'peso', 'talla', 'idAgenda', 'idEspecialista', 'idEspecialidad']);
    }

    #[On('guardarSignosVitales')]
    public function guardarSignosVitales()
    {

        $this->validate();

        try {
            $signoVitalExistente = SignoVital::where('id_agenda', $this->idAgenda)->first();

            $datosParaGuardar = [
                'presion_arterial'        => $this->presionArterial,
                'frecuencia_respiratoria' => $this->frecuenciaRespiratoria,
                'temperatura'             => $this->temperatura,
                'frecuencia_cardiaca'     => $this->frecuenciaCardiaca,
                'saturacion_oxigeno'      => $this->saturacionOxigeno,
                'peso'                    => $this->peso,
                'talla'                   => $this->talla,
                'id_user_updated'         => auth()->id(),
            ];

            if ($signoVitalExistente) {
                $signoVitalExistente->update($datosParaGuardar);
            } else {
                $datosParaGuardar['id_agenda']       = $this->idAgenda;
                $datosParaGuardar['id_especialista'] = $this->idEspecialista;
                $datosParaGuardar['id_especialidad'] = $this->idEspecialidad;
                $datosParaGuardar['id_user_created'] = auth()->id();
                SignoVital::create($datosParaGuardar);
            }

            $this->resetearValores();
            $this->dispatch('pg:eventRefresh-AgendaGeneralTable');
            //$this->dispatch('signosVitalesGuardados'); // O la alerta que uses

        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error("Error al guardar signos vitales en agenda {$this->idAgenda}: " . $th->getMessage());
            $this->dispatch('signos_vitales_error', ['message' => 'Ocurrió un error al intentar guardar los datos.']);
        }
     
    }
}
