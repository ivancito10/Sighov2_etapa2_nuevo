<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Agenda;

use App\Models\Administracion\LinkServicio;
use App\Services\RespuestaApisService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class AgendaAnticipadaMain extends Component
{
    public $fechaAnticipadaFiltro;

    public $datosPaciente;

    public $nombrePaciente;

    public $edadPaciente;

    public $generoPaciente;

    public $turnos = [];

    public $horarios;

    public $turnoSeleccionado = 0;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.agenda.agenda-anticipada-main');
    }

    public function mount()
    {
        //$this->agenda = session('agenda_anticipada_id');
        // $this->datosPaciente = session('agenda_anticipada_id');
        // //dd($this->datosPaciente);
        // $this->nombrePaciente = $this->datosPaciente['nombre_persona'] ?? '';
        // $this->edadPaciente = $this->datosPaciente['edad'] ?? '';
        // $this->generoPaciente = $this->datosPaciente['genero'] ?? '';
        // $this->fechaAnticipadaFiltro = now()->addDay()->format('Y-m-d');
        //dd($this->agenda);
    }

    #[On('modalAgendaAnticipada')]
    public function modalAgendaAnticipada($paciente)
    {
        //dd('dentra', $paciente);
        $this->datosPaciente = $paciente;
        $this->fechaAnticipadaFiltro = '';
        $this->turnos = [];
    }

    public function buscarAgenda()
    {
        $this->filtrarPorFechaAnticipada();
    }

    public function filtrarPorFechaAnticipada()
    {   //dd($this->fechaAnticipadaFiltro);
        //$this->dispatch('fechaAnticipadaSeleccionada', fecha: $this->fechaAnticipadaFiltro);
        $hoy = Carbon::today();
        $fechaMaxima = $hoy->copy()->addMonth();

        if (Carbon::parse($this->fechaAnticipadaFiltro)->lte($fechaMaxima)) {
            $this->cargarTurnosYHorarios($this->fechaAnticipadaFiltro);
            //dd($this->cargarTurnosYHorarios($this->fechaAnticipadaFiltro));
            $this->dispatch('fechaAnticipadaSeleccionada', horario: $this->horarios);
            //$this->skipRender();
        } else {
            $this->dispatch('fechaInvalida');
            $this->horarios = [];
            $this->turnos = [];
            $this->dispatch('fechaAnticipadaSeleccionada', horario: $this->horarios);
        }
    }

    public $data_select = [];

    #[On('seleccionarAgenda')]
    public function seleccionarAgenda($data)
    {
        $this->data_select = $data;
        $this->dispatch('confirmarAgenda', [
            'hora' => $data['hora'],
            'fecha' => Carbon::parse($this->fechaAnticipadaFiltro)->format('d/m/Y'),
        ]);
    }

    #[On('registrarAgendaAnticipadaSeleccionada')]
    public function registrarAgendaAnticipadaSeleccionada($motivo)
    {
        //dd($motivo);
        $data = $this->data_select;
        //dd($data);
        $datos = [
            'id_persona' => $this->datosPaciente['id_persona'],
            'id_user' => auth()->user()->id,
            'fechaElegida' => $this->fechaAnticipadaFiltro,
            'horaElegida' => $data['hora'],
            'id_asignacion_horarioElegido' => $this->turnos[$this->turnoSeleccionado]['id_asignacion_horario'],
            'ip' => request()->ip(),
            'motivo_anticipada' => $motivo,
            // "nombre_equipo" => gethostname(),
        ];
        //dd($datos);
        $token = session('access_token', '');
        $link = LinkServicio::where('nombre_servicio', 'FICHA ANTICIPADA')->pluck('link_servicio')->first();
        $response = Http::withToken($token)->post($link, $datos);

        // GUARDAR EL LOG DE LAS REPLICAS
        // $respuesta = Http::post($urlSigho1, $data);
        $id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $data,
            $response,
            'SIGHO V1',
            'FICHA ANTICIPADA - CONSULTA EXTERNA - SIGHO V1',
            'PLATAFORMA - SIGHO V2',
            $id_user_created
        );
        // JSON EXISTE AGENDA POR AGENDA
        if ($response->json('status') == 200) {
            $data = $response->json();
            $id_agenda = $data['agenda']['id'];
            $this->js('window.open("/atencion_medica/consulta_externa/pdf_ficha_anticipada/'.$id_agenda.'", "_blank")');
            $this->dispatch('fichaAnticipadaRegistrada');
        } elseif ($response->json('status') == 404 && $response->json('message') == 'Ya existe un familiar agendado en la fecha seleccionada.') {
            // Extraemos la información de la persona
            $persona = $response->json('agenda')['persona_agenda'];

            $this->dispatch('alertaPersonalizada', [
                'title' => 'El Asegurado o un miembro de su grupo familiar ya cuenta con una ficha anticipada.',
                'text' => $response->json('message'),
                'icon' => 'warning',
                // Enviamos el objeto completo para que tu JS pueda acceder a sus propiedades
                'persona_agenda' => [
                    'nombres' => $persona['nombres'],
                    'p_apellido' => $persona['p_apellido'],
                    's_apellido' => $persona['s_apellido'],
                    'matricula_seguro' => $persona['matricula_seguro'],
                ],
            ]);
        } elseif ($response->status() == 403) {
            $this->dispatch('limiteSuperado');
        } else {
            dd('else', $response->json());
            $this->dispatch('error');
        }
    }

    public function cargarTurnosYHorarios($fechaAnticipadaFiltro)
    {
        $id_especialista = session('id_especialista');
        $token = session('access_token', '');
        $link = LinkServicio::where('nombre_servicio', 'AGENDA MEDICO TURNOS')->pluck('link_servicio')->first();
        $response = Http::withToken($token)->post($link, [
            'fecha_agenda' => $fechaAnticipadaFiltro,
            'id_especialista' => $id_especialista,
        ]);
        //dd($fechaAnticipadaFiltro,$response,$response->json());
        $this->horarios = [];
        $this->turnos = [];
        if ($response->successful()) {
            $datos = collect($response->json('data'));

            $this->turnos = $datos->flatMap(function ($turno) {
                // Si el estado es falso (o no viene definido), lo ignoramos devolviendo un array vacío
                if (! isset($turno['estado']) || ! $turno['estado']) {
                    return [];
                }

                // Si está activo, lo preparamos para la colección
                return [[
                    'nombre' => $turno['turno'], // Ej: MAÑANA, TARDE, NOCHE
                    'horarios' => $turno['horarios'] ?? [],
                    'id_asignacion_horario' => $turno['id_asignacion_horario'],
                    'estado' => $turno['estado'], // Guardamos el estado por seguridad si lo necesitas
                ]];
            })
                ->map(function ($turno, $index) {
                    // Asignamos el índice correlativo real (0, 1, 2...) después de haber filtrado los inactivos
                    $turno['index'] = $index;

                    return $turno;
                })
                ->toArray();
            //dd($this->turnos);
            $this->actualizarHorarios();
        } else {
            $this->dispatch('medicoSinHorarios');
        }
    }

    public function actualizarHorarios()
    {
        $this->horarios = $this->turnos[$this->turnoSeleccionado]['horarios'] ?? [];
        //dd($this->fechaAnticipadaFiltro,$this->turnos, $this->horarios);
    }
}
