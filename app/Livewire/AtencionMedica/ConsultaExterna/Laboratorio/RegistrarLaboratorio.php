<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Laboratorio;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\Laboratorio\AdministracionPlazoSolicitudLaboratorio;
use App\Models\Laboratorio\AdministracionVistaLaboratorio;
use App\Models\Laboratorio\LabExamenes;
use App\Models\Laboratorio\Solicitudes;
use App\Models\Laboratorio\SolicitudExamen;
use App\Services\EspecialidadService;
use App\Services\RespuestaApisService;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class RegistrarLaboratorio extends Component
{
    public $datoHistoriaClinica;

    public $observaciones;

    public $tipos_laboratorio = [];

    public $tipo_lab_padre;

    public $orden = [];

    public $examenes_seleccionados = [];

    public $tipo_consulta = null;

    public $examenesSeleccionados = []; //Creado para el checkbox de laboratorios By Risbou

    public $configuracionVista;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.laboratorio.registrar-laboratorio');
    }

    public function mount()
    {
        $existeConfiguracion = AdministracionVistaLaboratorio::where('id_user_especialista', auth()->user()->id)
            ->first();

        if ($existeConfiguracion !== null) {
            $this->configuracionVista = $existeConfiguracion->casillas_checks; //Si el booleanito este esta en true va a ser que son check y si esta en false es que esta con el multiselect By RisBou
        } else {
            $this->configuracionVista = $this->registrarNuevaConfiguracion(auth()->user()->id);
        }

        //dd($this->configuracionVista, auth()->user()->id);
        $this->tipo_consulta = request()->query('tipo_at');
    }

    public function registrarNuevaConfiguracion($idUserEspecialista)
    {
        $nuevaConfiguracion = AdministracionVistaLaboratorio::create([
            'id_user_especialista' => $idUserEspecialista,
            'casillas_checks' => true,
            'id_user_created' => auth()->user()->id,
        ]);

        $configuracionCasillas = true;

        if ($nuevaConfiguracion) {
            $configuracionCasillas = $nuevaConfiguracion->casillas_checks;
        }

        return $configuracionCasillas;
    }


    #[On('modal_laboratorio')]
    public function cargarDato($dato)
    {
        /*$pruebita = LabExamenes::with('AdministracionPlazoSolicitudLaboratorioLabExamenes')->limit(20)->get();
        $pruebita2 = AdministracionPlazoSolicitudLaboratorio::with('labExamenesAdministracionPlazoSolicitudLaboratorio')->limit(30)->get();
        dd($pruebita);*/
        //tipo de laboratorio
        $this->examenes_seleccionados = [];
        $this->observaciones = '';
        // $link_servicio = LinkServicio::where('nombre_servicio', 'LISTAR EXAMENES')->pluck('link_servicio')->first();
        // $token = session('access_token', '');
        // $response = Http::withToken($token)->post(
        //     $link_servicio,
        //     ['id_tipo' => $dato],
        // );
        //dd($link_servicio,$response, $response->json());
        $datos = $this->listar_examenes($dato);
        //dd($datos);
        $this->tipo_lab_padre = $datos['nombre'] ?? null;
        $this->tipos_laboratorio = $datos['data']->toArray() ?? [];
        //$this->verificarPlazoLaboratorio($this->tipos_laboratorio);
        //dd($this->tipo_lab_padre, $this->tipos_laboratorio);
        $this->dispatch('abrirModalLaboratorio');
    }

    /*public function verificarPlazoLaboratorio($datosArrayPlazoLaboratorio)       //Esto era lo que estaba probando
    {
        $solicitudesPasadas = Solicitudes::with('examenesSolicitud')
            ->where('id_persona_asegurado', $this->datoHistoriaClinica['id_persona'])
            ->get()->examenesSolicitud;
        dd($this->datoHistoriaClinica['id_persona'], $solicitudesPasadas);
    }*/

    #[On('recibirHCLaboratorio')]
    public function recibirHCLaboratorio($data)
    {
        $this->datoHistoriaClinica = $data;
    }

    public function agregarExamenes($examenes)
    {
        //dd($examenes);
        $this->examenes_seleccionados = $examenes;
        $this->skipRender();
    }

    #[On('resetearArrays')]
    public function resetearArrays()
    {
        $this->examenesSeleccionados = [];
        $this->examenes_seleccionados = [];
    }

    #[On('guardarLaboratorio')]
    public function registrarLab()
    {
        $arraySegunConfiguracionVista = $this->configuracionVista ? $this->examenesSeleccionados : $this->examenes_seleccionados;
        try {
            DB::beginTransaction();
            //dd($this->examenes_seleccionados, $this->datoHistoriaClinica);
            $existeExamenesSelect = Solicitudes::where('id_historia_clinica', $this->datoHistoriaClinica['id'])
                ->where('id_persona_asegurado', $this->datoHistoriaClinica['id_persona'])
                ->whereHas('examenesSolicitud', function ($query) use ($arraySegunConfiguracionVista) {
                    $query->whereIn('id_examen', $arraySegunConfiguracionVista); //Antiguo codigo
                    //$query->whereIn('id_examen', $this->examenesSeleccionados);
                })
                ->with('examenesSolicitud')
                ->first();
            if ($existeExamenesSelect) {
                $idsExamenesSolicitud = $existeExamenesSelect->examenesSolicitud
                    ->pluck('id_examen')
                    ->map(fn($id) => (int) $id)
                    ->toArray();

                //$idsCoincidentes = array_intersect($this->examenes_seleccionados, $idsExamenesSolicitud);    //Antiguo codigo
                //$idsCoincidentes = array_intersect($this->examenesSeleccionados, $idsExamenesSolicitud);

                $idsCoincidentes = array_intersect($arraySegunConfiguracionVista, $idsExamenesSolicitud);

                if (! empty($idsCoincidentes)) {
                    $examenesCoincidentes = $existeExamenesSelect->examenesSolicitud
                        ->whereIn('id_examen', $idsCoincidentes)
                        ->pluck('nombre_examen');
                    $this->skipRender();

                    return $this->dispatch('examenesDuplicados', message: $examenesCoincidentes);
                }
            }

            //dd($examenes,$existeExamenesSelect->examenesSolicitud->pluck('id_examen'));
            //$examenes_select = collect($this->examenes_seleccionados)->map(fn($v) => (int) $v)->values()->all(); // [197, 13]  //Antiguo codigo
            //$examenes_select = collect($this->examenesSeleccionados)->map(fn($v) => (int) $v)->values()->all();

            $examenes_select = collect($arraySegunConfiguracionVista)->map(fn($v) => (int) $v)->values()->all();

            //dd($examenes_select);
            $paciente = Persona::find($this->datoHistoriaClinica['id_persona']);
            $servicio_per = new ServicioPersona;
            $paciente_insti = $servicio_per->datosPersona($this->datoHistoriaClinica['id_persona']);
            $servicio_especialistas = new EspecialidadService;
            $lista_especialista = $servicio_especialistas->obtenerEspecialistas();
            // $servicio_insti_pac = new ServicioPersonaInstitucion();
            // $institucion_pac = $servicio_insti_pac->personaInstitucion($paciente->ci);  <-- este devuelve null
            $esp = $lista_especialista->firstWhere('id', $this->datoHistoriaClinica['id_especialista']);
            //yeison listo :u
            $ultimoNumero = Solicitudes::max('nro_solicitud');
            $numeroCorrelativo = $ultimoNumero ? $ultimoNumero + 1 : 1;
            $datosSol = [
                'nro_solicitud' => $numeroCorrelativo, //??
                'id_historia_clinica' => $this->datoHistoriaClinica['id'],
                'id_persona_asegurado' => $this->datoHistoriaClinica['id_persona'],
                'id_persona_solicitante' => $esp['persona_especialista']['id'],
                'observacion_solicitud' => $this->observaciones,
                'id_tipo_atencion' => $this->tipo_consulta,
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];

            $sol_creada = Solicitudes::create($datosSol);
            if ($sol_creada) {
                $listaLab = $this->tipos_laboratorio;
                foreach ($listaLab as $lab_padre) {

                    foreach ($arraySegunConfiguracionVista as $exam) {

                        //foreach ($this->examenes_seleccionados as $exam) {           //Antigua linea
                        //foreach ($this->examenesSeleccionados as $exam) {
                        //dd(array_key_exists('examenes_hijo', $lab_padre), collect($lab_padre['examenes_hijo']));
                        //REVISAR DESPUES
                        $examenDato = array_key_exists('examenes_hijo', $lab_padre) ? collect($lab_padre['examenes_hijo'])->firstWhere('id', (int) $exam) : $lab_padre;
                        //dd($examenDato);
                        if ($examenDato) {
                            //dd($examenDato,$lab_padre['examenes_hijo'],$exam,$lab_padre,$this->tipos_laboratorio);
                            //$examenDato['nombre'] = '';
                            $datosSolExam = [
                                'id_examen' => $exam,
                                'id_solicitudes' => $sol_creada->id,
                                'nombre_examen' => array_key_exists('nombre_examen', $examenDato) ? $examenDato['nombre_examen'] : $examenDato['nombre'],
                                'grupo' => $lab_padre['nombre_examen'] ?? '',
                                'tipo_laboratorio' => $this->tipo_lab_padre,
                                'estado' => true,
                                'id_user_created' => auth()->user()->id,
                                'created_at' => now(),
                            ];
                            //dd($datosSolExam);
                            SolicitudExamen::create($datosSolExam);
                        }
                    }
                }

                $datos = [
                    'nombres' => $paciente->nombres ?? null,
                    'ap_Paterno' => $paciente->p_apellido ?? null,
                    'ap_Materno' => $paciente->s_apellido ?? null,
                    'fecha_nacimiento' => $paciente->fecha_nacimiento ?? null,
                    'genero' => $paciente->sexo ?? null,
                    'numero_identificacion' => $paciente->ci . ($paciente->complemento ? '-' . $paciente->complemento : ''),
                    'telefono1' => '0',
                    'tipo_asegurado' => $paciente->id_tipo_asegurado ?? null,
                    'matricula' => $paciente->matricula_seguro ?? null,
                    'codigo' => 'C2',
                    'institucion' => $paciente_insti['institucion'] ?? null,
                    'observacion_solicitud' => $this->observaciones ?? null,
                    'id_tipo_atencion' => 1, //tipo consulta modificar!!
                    'id_solicitud_s2' => $sol_creada->id,
                    'medico' => [
                        'nombres' => $esp['persona_especialista']['nombres'] ?? null,
                        'ap_Paterno' => $esp['persona_especialista']['p_apellido'] ?? null,
                        'ap_Materno' => $esp['persona_especialista']['s_apellido'] ?? null,
                        'fecha_nacimiento' => $esp['persona_especialista']['fecha_nacimiento'] ?? null,
                        'genero' => $esp['persona_especialista']['sexo'] ?? null,
                        'numero_identificacion' => $esp['persona_especialista']['ci'] . ($esp['persona_especialista']['complemento'] ? '-' . $esp['persona_especialista']['complemento'] : ''),
                        'telefono1' => '0',
                    ],
                    'examenes' => $examenes_select,
                ];
                //dd($datos);

                $link = LinkServicio::where('nombre_servicio', 'REPLICA LABORATORIO SIGHO 1')->pluck('link_servicio')->first();
                $token = session('access_token', '');
                $response = Http::withToken($token)->post($link, $datos);
                //dd($response);
                // guardar los los de las repclicas para ver que pedo
                $id_user_created = auth()->user()->id;
                $replica = new RespuestaApisService();

                $replica->guardarRespuesta(
                    $datos,
                    $response,
                    "sigho 1 laboratorio",
                    "Laboratorio SIGHO1",
                    "consulta externa SIGHOV2",
                    $id_user_created
                );

                //dd($datos);
                if ($response->status() == 200) {
                    $respuesta_servicio = $response->json('data');
                    $id_solicitud_s1 = $respuesta_servicio['id_solicitud'];

                    $sol_creada->update([
                        'id_solicitud_s1' => $id_solicitud_s1
                    ]);

                    DB::commit();
                    // dd($response);
                    $this->reset('examenes_seleccionados', 'observaciones');
                    $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], 2, $sol_creada->id);
                    $this->js('window.open("/consulta_externa/pdf/laboratorio/' . $sol_creada->id . '", "_blank")');
                    $this->dispatch('pg:eventRefresh-LaboPersonaTable');
                    $this->dispatch('laboratorioRegistrado');
                } else {
                    //dd($response->json());
                    DB::rollBack();
                    $this->dispatch('errorLaboratorio', message: 'Ocurrio un error en la comunicación con el servicio de laboratorio');
                }
            }
            $this->resetearArrays();
        } catch (Throwable $e) {
            //dd($e);
            DB::rollBack();
            $this->dispatch('errorLaboratorio', message: 'Ocurrio un error al registrar el laboratorio, comuniquese con la unidad de sistemas');
            Log::error('Error al registrar Laboratorio' . $e->getMessage());
        }
    }

    // alo
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

    public function listar_examenes($id_tipo)
    {
        switch ($id_tipo) {

            case '1':
                return [
                    'id_tipo' => $id_tipo,
                    'nombre'  => 'LAB CLINICO',
                    'data'    => LabExamenes::whereNull('tipo')
                        //->with('examenes_hijo', 'AdministracionPlazoSolicitudLaboratorioLabExamenes')
                        ->with('examenes_hijo')
                        ->where('estado', '=', 1)
                        ->whereNull('id_examen_maestro')
                        ->get() // tipo LAB CLINICO
                ];
                break;

            case '2':
                return [
                    'id_tipo' => $id_tipo,
                    'nombre'  => 'BACTERIOLOGIA',
                    'data'    => LabExamenes::where('tipo', '=', 2)
                        //->with('examenes_hijo', 'AdministracionPlazoSolicitudLaboratorioLabExamenes')
                        ->with('examenes_hijo')
                        ->where('estado', '=', 1)
                        ->whereNull('id_examen_maestro')
                        ->get()
                ];
                break;

            case '3':
                return [
                    'id_tipo' => $id_tipo,
                    'nombre'  => 'PATOLOGIA',
                    'data'    => LabExamenes::where('tipo', '=', 3)
                        //->with('examenes_hijo', 'AdministracionPlazoSolicitudLaboratorioLabExamenes')
                        ->with('examenes_hijo')
                        ->where('estado', '=', 1)
                        ->whereNull('id_examen_maestro')
                        ->get()
                ];
                break;

            case '4':
                return [
                    'id_tipo' => $id_tipo,
                    'nombre'  => 'LAB EXTERNO',
                    'data'    => LabExamenes::where('tipo', '=', 4)
                        //->with('examenes_hijo', 'AdministracionPlazoSolicitudLaboratorioLabExamenes')
                        ->with('examenes_hijo')
                        ->where('estado', '=', 1)
                        ->whereNull('id_examen_maestro')
                        ->get()
                ];
                break;

            case '5':
                //dd('llega');
                return [
                    'id_tipo' => $id_tipo,
                    'nombre'  => 'OTROS LABS',
                    'data'    => LabExamenes::where('tipo', '=', 5)
                        //->with('examenes_hijo', 'AdministracionPlazoSolicitudLaboratorioLabExamenes')
                        ->with('examenes_hijo')
                        ->where('estado', '=', 1)
                        ->whereNull('id_examen_maestro')
                        ->get()
                ];
                break;

            default:
                abort(404, 'No se encontro el tipo de examenes');
                break;
        }
    }
}
