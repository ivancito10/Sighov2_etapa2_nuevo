<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\Formulario;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Services\ObtenerToken;
use App\Services\RespuestaApisService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class ConsultaExternaMain extends Component
{
    public $fechaFiltro;

    public $fechaAnticipadaFiltro;

    public $datosPaciente;

    public $datosAgendaPaciente;

    public $asignaciones_horario;

    public $nombrePaciente;

    public $edadPaciente;

    public $generoPaciente;

    public $turnos = [];

    public $horarios;

    public $turnoSeleccionado = 0;

    public $id_asig_horario;
    public $fecha, $fecha_hoy,$datos_agenda;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.historia-clinica.consulta-externa-main');
    }

    public function consulta_externa_register()
    {
        return redirect()->route('atencion_medica.consulta_externa.create');
    }

    public function mount()
    {
        $token = new ObtenerToken();
        $token->obtenerToken();
        //dd($token->obtenerToken());
        $this->fechaFiltro = now()->format('Y-m-d');
        $this->fecha_hoy = true;
        // dd(auth()->user()->id_persona);
    }

    public function filtrarPorFecha()
    {
        $this->dispatch('fecha-agenda-actualizada', fecha: $this->fechaFiltro);
        $this->fecha_hoy = ($this->fechaFiltro === now()->format('Y-m-d'));
        $this->dispatch('fechaFiltrada');
    }

    #[On('registrarAgendaAnticipada')]
    public function registrarAgendaAnticipada($data)
    {
        // $this->fechaAnticipadaFiltro = now()->addDay()->format('Y-m-d');
        //en vista
        // session(['agenda_anticipada_id' => $data]);
        // return redirect()->route('atencion_medica.historia_clinica.anticipada');

        // $this->cargarTurnosYHorarios($this->fechaAnticipadaFiltro);
        //en modal
        // $this->datosAgendaPaciente = $data;
        // $this->nombrePaciente = $data['nombre_persona'];
        // $this->edadPaciente = $data['edad'];
        // $this->generoPaciente = $data['genero'];
        //dd($this->fechaAnticipadaFiltro,$this->datosAgendaPaciente['id_asignacion_horario']);
        $this->dispatch('modalAgendaAnticipada', paciente: $data);
    }
    // public function filtrarPorFechaAnticipada()
    // {
    //     $this->cargarTurnosYHorarios($this->fechaAnticipadaFiltro);
    //     $this->dispatch('fechaAnticipadaSeleccionada', horario: $this->horarios);
    //     $this->skipRender();
    // }

    #[On('registrarFichaExtra')]
    public function registrarFichaExtra()
    {
        // dd('llega');
        $this->dispatch('modalFichaExtraRegister');
    }

    #[On('registrarReporteAtencion')]
    public function registrarReporteAtencion()
    {
        $this->turnos = $this->obtenerIdAsignacionHorario();
        $this->dispatch('modalReporteAtencion');
    }

    #[On('generarPdfReporteAtencion')]
    public function generarPdfReporteAtencion()
    {
        $id_asig = $this->id_asig_horario;
        $fecha = $this->fechaFiltro;
        $this->js('window.open("/consulta_externa/pdf_reportes_atencion/' . $id_asig . '/' . $fecha . '", "_blank")');
        $this->dispatch('cerrarModalReporteAtencion');
    }

    #[On('registrarAgendaExtraSelect')]
    public function registrarAgendaExtraSelect($data)
    {
        //dd('llega extra',$data);
        $this->datosPaciente = $data;
        //consultar si se puede agendar
        $token = session('access_token', '');
        $fecha_filtro = $this->fecha_agenda ?? now()->format('Y-m-d');
        $link = LinkServicio::where('nombre_servicio', 'VERIFICAR VIGENCIA PERSONA')->pluck('link_servicio')->first();
        $response = Http::withToken($token)
            ->post($link, [
                'id_persona' => $this->datosPaciente['id'],
                'fecha_agenda' => $fecha_filtro,
            ]);
        //dd($link,$response, $response->json(), auth()->user()->id_persona, $fecha_filtro);
        if ($response->json('data')) {
            $this->asignaciones_horario = $this->obtenerIdAsignacionHorario();
            //dd($this->asignaciones_horario);
            $this->dispatch(
                'llenarMotivo',
                datosPaciente: $this->datosPaciente,
                turnos: $this->asignaciones_horario
            );
        } else {
            //dd($response->json());
            $this->dispatch('fichaExtraObservada');
        }

        //dd($asignaciones_horario);
    }

    #[On('atenderAgenda')]
    public function atenderAgenda($data)
    {
 
        session(['agenda_data' => $data]);
        $this->datos_agenda = $data;
        
        return redirect()->route('atencion_medica.historia_clinica.index', [
            'datos_agenda' => $this->datos_agenda
        ]);
    }

    #[On('registrarFichaExtraServicio')]
    public function registrarFichaExtraServicio($turno, $motivo)
    {
        $id_asignacion_horario = (int) $turno;
        $id_persona = Persona::where('ci', $this->datosPaciente['ci'])
            ->where('complemento', $this->datosPaciente['complemento'])
            ->value('id');
        $datos = [
            'fecha_agenda' => now()->format('Y-m-d'),
            'id_asignacionHorario' => $id_asignacion_horario,
            'ip' => request()->ip(),
            'id_user' => auth()->user()->id,
            'id_persona' => $id_persona,
            'motivo' => $motivo,
            'nombre_equipo' => gethostname(),
        ];
        //dd($datos);
        $token = session('access_token', '');
        $link = LinkServicio::where('nombre_servicio', 'FICHA EXTRA')->pluck('link_servicio')->first();
        $response = Http::withToken($token)
            ->post($link, $datos);

        // REPLICA FICHA EXTRA
        $id_user_created = auth()->user()->id;
        $replica = new RespuestaApisService();
        $replica->guardarRespuesta(
            $datos,
            $response,
            'SIGHO V1',
            'FICHA ANTICIPADA - CONSULTA EXTERNA - SIGHO V1',
            'PLATAFORMA - SIGHO V2',
            $id_user_created
        );
        //dd($response->status());
        if ($response->successful()) {
            $data = $response->json();
            $this->dispatch('fichaExtraRegistrada');
            $this->dispatch('pg:eventRefresh-AgendaTable');
        } else {
            $this->dispatch('error');
        }
    }

    public function obtenerIdAsignacionHorario()
    {
       // dd('sdsada');
        $token = session('access_token', '');
        $fecha_filtro = $this->fecha_agenda ?? now()->format('Y-m-d');
        $link = LinkServicio::where('nombre_servicio', 'AGENDA TRABAJO')->pluck('link_servicio')->first();
        $response = Http::withToken($token)
            ->post($link, [
                'id_persona' => auth()->user()->id_persona,
                'fecha_agenda' => $fecha_filtro,
            ]);
        // dd($response,$response->json(),$response->json('message'));
        if ($response->successful()) {
            $data = collect($response->json('data'));
            // dd($data);
            $idsAsignacionHorario = $data->flatMap(function ($item) {
                // Si no está activo, devolvemos un array vacío para que flatMap lo elimine
                if (! $item['estado']) {
                    return [];
                }

                // Si está activo, devolvemos el array dentro de otro array para mantener la estructura
                return [[
                    'turno' => $item['turno'],
                    'id_asignacion_horario' => $item['id_asignacion_horario'],
                    'hora_inicio' => $item['hora_inicio'],
                    'hora_fin' => $item['hora_fin'],
                ]];
            })->unique('id_asignacion_horario')->values();
            // dd($idsAsignacionHorario);

            return $idsAsignacionHorario;
        } elseif ($response->json('message') == 'No hay turnos para esta fecha del especialista') {
            $this->dispatch('SinAsignacionHorario');

            return '';
        } elseif ($response->json('status') == 404) {
            $this->dispatch('SinDatosEspecialista');

            return '';
        } else {
            $this->dispatch('error');

            return '';
        }
        //listo para enviar id especialista
        //$idAsignacionHorario = $primerElemento['asignacion_horario_agenda']['id_especialista'];
        //dd($idAsignacionHorario);

    }

    #[On('confirmarFalta')]
    public function confirmarFalta($data)
    {
        $this->datosAgendaPaciente = $data;
        $this->dispatch('confirmacionFalta');
    }

    #[On('registrarFalta')]
    public function registrarFalta($motivo)
    {   //dd($motivo,$this->datosAgendaPaciente);
        $link = LinkServicio::where('nombre_servicio', 'ASISTENCIA AGENDA')
            ->pluck('link_servicio')
            ->first();
        $token = session('access_token', '');
        $datos = [
            'id_agenda' => $this->datosAgendaPaciente['id'],
            'asistencia' => false,
        ];
        //dd($datos);
        $response = Http::withToken($token)->put($link, $datos);
        //dd($response, $response->json('data'),$this->datosAgendaPaciente['id']);

        if ($response->successful()) {
            $datosCrear = [
                'id_agenda' => $this->datosAgendaPaciente['id'],
                'id_tipo_consulta' => 1,
                'id_persona' => $this->datosAgendaPaciente['id_persona'],
                'id_especialidad' => $this->datosAgendaPaciente['asignacion_horario_agenda']['especialista_asignacion_horario']['id_especialidad'],
                'reconsulta' => false,
                'id_historia_clinica' => null,
                'asistencia' => false,
                'fecha_hora' => Carbon::parse($this->datosAgendaPaciente['fecha_agenda'] . ' ' . $this->datosAgendaPaciente['hora_agenda'])->toDateTimeString(),
                'id_especialista' => $this->datosAgendaPaciente['asignacion_horario_agenda']['id_especialista'],
                'motivo_consulta' => $motivo,
                'enfermedad_actual' => '',
                'objetivo' => '',
                'estado_consulta' => false,
                'descriptivo' => '',
                'estado_consulta' => false,
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];
            $actualizarHC = HistoriaClinica::create($datosCrear);
            if ($actualizarHC) {
                $this->js('window.open("/consulta_externa/pdf/c_e/' . $actualizarHC->id . '", "_blank")');
            }
            $this->dispatch('faltaRegistrada');
            $this->dispatch('pg:eventRefresh-AgendaTable');
        } else {
            dd($response, $response->json());
            Log::error('Error con el servicio ASISTENCIA AGENDA', [
                'status' => $response->status(),
                'url' => $link . $this->datosAgendaPaciente['id'],
                'body' => $response->body(),
                'user_id' => auth()->id() ?? 'guest',
            ]);
            $this->dispatch('error');
        }
    }

    #[On('llenarFormulario')]
    public function llenarFormulario($data)
    {
        session(['agenda_data' => $data]);

        return redirect()->route('atencion_medica.form_atencion_medica.index');
    }

    public function atras()
    {
        return redirect()->route('atencion_medica.consulta_externa.index');
    }

    public function finalizar_historia_clinica()
    {
        $link = LinkServicio::where('nombre_servicio', 'ASISTENCIA AGENDA')
            ->pluck('link_servicio')
            ->first();
        $token = session('access_token', '');
        $datos = [
            'id_agenda' => $this->datosAgenda['id'],
            'asistencia' => true,
        ];
        $response = Http::withToken($token)->put($link, $datos);
        //dd($response, $response->json('data'),$this->datosAgenda['id']);

        if ($response->successful()) {
            //crear formulario de atencion medica
            // $fecha_actual = now()->toDateString();
            // $existe = Formulario::whereDate('created_at', $fecha_actual)
            //     ->where('id_asignacion_horario', $this->datosAgenda['id_asignacion_horario'])
            //     ->exists();

            // if (!$existe) {
            //     $datos_formulario = [
            //         'id_persona_enfermera' => null,
            //         'id_asignacion_horario' => $this->datosAgenda['id_asignacion_horario'],
            //         'id_user_created' => auth()->user()->id,
            //         'id_user_updated' => auth()->user()->id,
            //         'created_at' => now(),
            //     ];
            //     Formulario::create($datos_formulario);
            // }

            $actualizarHC = HistoriaClinica::find($this->id_historia_clinica);
            if ($actualizarHC) {
                $actualizarHC->estado_consulta = true;
                $actualizarHC->observacion_planes = $this->observacion_planes;
                $actualizarHC->save();
                $this->dispatch('soapCreada');
            }

            $this->js('window.open("/consulta_externa/pdf/c_e/' . $this->id_historia_clinica . '", "_blank")');

            return redirect()->route('atencion_medica.consulta_externa.index');
        } else {
            //dd($response->status(),$response->json());
            Log::error('Error con el servicio ASISTENCIA AGENDA', [
                'status' => $response->status(),
                'url' => $link . $this->datosAgenda['id'],
                'body' => $response->body(),
                'user_id' => auth()->id() ?? 'guest',
            ]);
            $this->dispatch('error');

            return redirect()->route('atencion_medica.historia_clinica.index');
        }
    }
}
