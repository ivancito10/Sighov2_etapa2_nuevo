<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica;

use App\Livewire\AtencionMedica\ConsultaExterna\BajaMedica\RegistrarBajaMedica;
use App\Livewire\AtencionMedica\ConsultaExterna\Fisioterapia\RegistrarFisioterapia;
use App\Livewire\AtencionMedica\ConsultaExterna\Imagenologia\RegistrarImagenologia;
use App\Livewire\AtencionMedica\ConsultaExterna\Interconsulta\RegistrarInterconsulta;
use App\Livewire\AtencionMedica\ConsultaExterna\Internacion\RegistrarInternacion;
use App\Livewire\AtencionMedica\ConsultaExterna\Laboratorio\RegistrarLaboratorio;
use App\Livewire\AtencionMedica\ConsultaExterna\Receta\RegistrarReceta;
use App\Livewire\AtencionMedica\ConsultaExterna\Referencia\RegistrarReferencia;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\Cie10;
use App\Models\ConsultaExterna\Formulario;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\TipoImagenologia;
use App\Models\Enfermeria\SignoVital;
use App\Services\ObtenerToken;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ConsultaExternaRegister extends Component
{
    public $foto_personal;

    public $institucion;

    public $alergias;

    public $datosAgenda;

    public $reconsulta;

    public $id_historia_reconsulta = null;

    public $datos_paciente;

    public $edad;

    public $datos_cie_10;

    public $hora_agenda;

    #[Validate]
    public $motivo_consulta;

    public $enfermedad_actual;

    public $objetivo;

    public $descriptivo;

    public $historialGuardado = false;

    public $planesRegistrados = [];

    //public $matricula ='VML118895 - B1', $nombre_completo= 'JUANA AZURDUY DE PADILLA', $ci='10909905-1E', $sexo='FEMENINO', $INSTITUCION='UNIVERSIDAD MAYOR DE SAN ANDRES';
    public $diagnosticosSeleccionados = [];

    public $diagnosticoDescriptivo;

    public $id_historia_clinica = null;

    public $tipos_imagenologia;

    public $tipo_img;

    public $id_per;

    public $tipos_laboratorio = [];

    public $codigo_institucional;

    public $nro_instituciones;

    public $instituciones;

    public $observacion_planes;

    public $estado_vigencia;

    public bool $mostrandoModalCie10 = false;

    // Coordenadas iniciales por defecto (milímetros)
    public $marginTop = 15;

    public $marginLeft = 15;

    public $sectorActivo = 'sup-izq';

    //Para signos vitales
    public $presionArterial;
    public $frecuenciaRespiratoria;
    public $temperatura;
    public $frecuenciaCardiaca;
    public $saturacionOxigeno;
    public $peso;
    public $talla;

    public $idEspecialista;
    public $idEspecialidad;
    public $modoEdicion;
    public $idAgendaActual;

    // Tabla de conversión táctil/visual a mm reales en la hoja Letter
    protected $sectores = [
        'sup-izq' => ['top' => 15,  'left' => 15],
        'sup-cen' => ['top' => 15,  'left' => 75],
        'sup-der' => ['top' => 15,  'left' => 135],

        'cen-izq' => ['top' => 110, 'left' => 15],
        'cen-cen' => ['top' => 110, 'left' => 75],
        'cen-der' => ['top' => 110, 'left' => 135],

        'inf-izq' => ['top' => 200, 'left' => 15],
        'inf-cen' => ['top' => 200, 'left' => 75],
        'inf-der' => ['top' => 200, 'left' => 135],
    ];

    // Función para cambiar de cuadrante cuando el médico haga clic
    public function seleccionarSector($nombreSector)
    {
        if (array_key_exists($nombreSector, $this->sectores)) {
            $this->sectorActivo = $nombreSector;
            $this->marginTop = $this->sectores[$nombreSector]['top'];
            $this->marginLeft = $this->sectores[$nombreSector]['left'];
        }
    }

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.historia-clinica.consulta-externa-register');
    }

    public function mount()
    {
        //$this->datosAgenda = json_decode(urldecode(request()->query('data')), true);
        // $this->datosAgenda = session('agenda_data');
        $this->datosAgenda = request()->query('datos_agenda');
        // dd($this->datosAgenda);
        $this->id_historia_reconsulta = request()->query('data');
        $this->reconsulta = filter_var(request()->input('reconsulta'), FILTER_VALIDATE_BOOLEAN);
        //dd($this->reconsulta);
        //dd($this->datosAgenda,$this->id_historia_reconsulta,$this->reconsulta, request()->query('reconsulta'));
        $this->mostrarDatos($this->datosAgenda);
        $this->datos_cie_10 = Cie10::all();

        $this->tipos_imagenologia = TipoImagenologia::where('estado', true)->orderBy('nombre', 'asc')->get();
        $link = LinkServicio::where('nombre_servicio', 'OBTENER ESPECIALISTA POR ID USER')->pluck('link_servicio')->first();
        $token = session('access_token', '');
        $datos = [
            'id_usuario' => auth()->id(),
        ];
        $response = Http::withToken($token)->post($link, $datos);
        $datoEspecialista = $response->json('data');
        if (! $datoEspecialista['estado']) {
            return $this->dispatch('medicoInhabilitado');
            //return redirect()->route('atencion_medica.otras_consultas.index');
        }
        $this->dispatch('modalRegistroSubjetivo');
    }

    public function rules()
    {
        return [
            'motivo_consulta' => 'required|string|min:1',
            'objetivo' => 'required|string|min:2',

            'presionArterial' => 'nullable|string',
            'frecuenciaRespiratoria' => 'nullable|integer',
            'temperatura' => 'nullable|numeric',
            'frecuenciaCardiaca' => 'nullable|integer',
            'saturacionOxigeno' => 'nullable|integer',
            'peso' => 'nullable|numeric',
            'talla' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'motivo_consulta.required' => 'El motivo de consulta es obligatorio.',
            'objetivo.required' => 'El objetivo es obligatorio.',

            'frecuenciaRespiratoria.integer' => 'La frecuencia respiratoria debe ser un número entero.',
            'temperatura.numeric' => 'La temperatura debe ser un número válido.',
            'frecuenciaCardiaca.integer' => 'La frecuencia cardíaca debe ser un número entero.',
            'saturacionOxigeno.integer' => 'La saturación de oxígeno debe ser un número entero.',
            'peso.numeric' => 'El peso debe ser un número válido.',
            'talla.numeric' => 'La talla debe ser un número válido.',
        ];
    }

    public function mostrarDatos($agendaData)
    {
        //dd($agendaData);
        $this->datos_paciente = Persona::find($agendaData['id_persona']);
        $this->id_per = $this->datos_paciente->id;

        $fechaNacimiento = $this->datos_paciente?->fecha_nacimiento ? \Carbon\Carbon::parse($this->datos_paciente->fecha_nacimiento) : null;
        $this->edad = $fechaNacimiento?->age;
        $this->hora_agenda = $agendaData['hora_agenda'];

        $id_persona = $agendaData['id_persona'];
        // Servicio para mostrar datos tipo asegurado e institucion del asegurado
        $dato_per = new ServicioPersona();
        $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
        $this->institucion = $datos_persona['institucion'] ?? null;
        $this->estado_vigencia = $datos_persona['estado_vigencia'] ?? null;
        $this->alergias = $datos_persona['alergias'] ?? '';

        //modificar foto http a https
        $urlFoto = $datos_persona['url_foto'] ?? '';

        // dd($urlFoto);
        $this->foto_personal = $urlFoto
            ? route('imagen.proxy', ['u' => $urlFoto])
            : '';
        // dd($this->foto_personal);
        // Servicio para mostrar las instituciones de la persona
        $dato_per = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_per->personaInstitucion($id_persona);

        // Asignar array vacío si no viene 'instituciones'
        $this->instituciones = $persona_institucion['instituciones'] ?? [];
        $this->nro_instituciones = count($this->instituciones);

        $this->codigo_institucional = null;
        if (! empty($this->instituciones)) {
            foreach ($this->instituciones as $institucion_individual) {
                if ($institucion_individual['nombre_institucion'] == $this->institucion) {
                    $this->codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                    break;
                }
            }
        } else {
            $this->codigo_institucional = null;
        }

        //Para mostrar signos vitales
        $this->idAgendaActual = $agendaData['id'] ?? null;

        if ($this->idAgendaActual) {
            $signosVitales = SignoVital::where('id_agenda', $this->idAgendaActual)->first();
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

    #[On('registrarSubjetivo')]
    public function registrarSubjetivo()
    {
        if (trim($this->motivo_consulta) === '') {
            $this->dispatch('mostrarAlertaCamposVacios');
            $this->skipRender();

            return; // No cerrar modal ni continuar
        }

        $this->dispatch('cerrarModalSubjetivo');
    }

    #[On('atrasSubjetivo')]
    public function atrasSubjetivo()
    {
        // dd($this->datos_agenda);
        return redirect()->route('atencion_medica.historia_clinica.index', [
        'datos_agenda' => $this->datosAgenda
    ]);
    }

    public function abrirModalCie10()
    {
        // 1. Activamos la variable para que el @if en el blade sea verdadero
        $this->mostrandoModalCie10 = true;

        // 2. Abrimos el modal (esto activa el JS de Bootstrap/AdminLTE que usas)
        $this->dispatch('modalSelectCie10');
    }

    // IMPORTANTE: Crea una función para cerrar y resetear
    public function cerrarModalCie10()
    {
        $this->mostrandoModalCie10 = false;
    }

    #[On('guardarRegistroHC')]
    public function guardarRegistroHC()
    {
        $this->validate();
        try {
            $token = new ObtenerToken();
            $token->obtenerToken();

            DB::beginTransaction();

            if ($this->diagnosticosSeleccionados == []) {
                return $this->dispatch('seleccioneDiagnosticoCie10');
            }
            //dd($this->datosAgenda);
            $datos = [
                'id_agenda' => $this->datosAgenda['id'],
                'id_tipo_consulta' => 1, //consulta externa
                'id_persona' => $this->datosAgenda['id_persona'],
                'id_especialidad' => $this->datosAgenda['asignacion_horario_agenda']['especialista_asignacion_horario']['id_especialidad'],
                'reconsulta' => $this->reconsulta,
                'id_historia_clinica' => $this->id_historia_reconsulta,
                'asistencia' => true,
                'fecha_hora' => Carbon::parse($this->datosAgenda['fecha_agenda'] . ' ' . $this->datosAgenda['hora_agenda'])->toDateTimeString(),
                'fecha_hora' => Carbon::parse($this->datosAgenda['fecha_agenda'] . ' ' . $this->datosAgenda['hora_agenda'])->toDateTimeString(),
                'id_especialista' => $this->datosAgenda['asignacion_horario_agenda']['id_especialista'],
                'motivo_consulta' => $this->motivo_consulta,
                'enfermedad_actual' => $this->enfermedad_actual,
                'objetivo' => $this->objetivo,
                'descriptivo' => $this->descriptivo,
                'estado_consulta' => false, //todavia no se registrara como true
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];
            //dd($datos);
            $datoCreado = HistoriaClinica::create($datos);
            $this->id_historia_clinica = $datoCreado->id;
            if (! empty($this->diagnosticosSeleccionados)) {
                // Agregar los diagnósticos seleccionados a la tabla intermedia
                foreach ($this->diagnosticosSeleccionados as $diagnostico) {
                    $datoCreado->HistoriaClinicaCie10()->attach($diagnostico->id);
                }
            }
            DB::commit();

            //PARA OBTENER LA INSTITUCION (SOLAMENTE LA PRIMERA) DEL ASEGURADO
            $primeraInstitucion = $this->instituciones !== [] ? $this->instituciones[array_key_first($this->instituciones)] : [];

            //dd($this->instituciones);
            $this->historialGuardado = true;
            //ENVIAR EL DATO HISTORIA CLINICA A CONTROLADORES
            $this->dispatch('recibirHCReceta', data: $datoCreado)
                ->to(RegistrarReceta::class);
            $this->dispatch('recibirHCLaboratorio', data: $datoCreado)
                ->to(RegistrarLaboratorio::class);
            $this->dispatch('recibirHCImagenologia', data: $datoCreado)
                ->to(RegistrarImagenologia::class);
            $this->dispatch('recibirHCBajaMedica', data: $datoCreado, instituciones: $primeraInstitucion)
                ->to(RegistrarBajaMedica::class);
            $this->dispatch('recibirHCFisioterapia', data: $datoCreado)
                ->to(RegistrarFisioterapia::class);
            $this->dispatch('recibirHCInternacion', data: $datoCreado)
                ->to(RegistrarInternacion::class);
            $this->dispatch('recibirHCReferencia', data: $datoCreado)
                ->to(RegistrarReferencia::class);
            $this->dispatch('recibirHCInterconsulta', data: $datoCreado)
                ->to(RegistrarInterconsulta::class);

            $this->dispatch('consultaExternaCreada');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            Log::error('Error al guardar el historial clínico: ' . $e->getMessage());
            Log::error('Error al guardar el historial clínico: ' . $e->getMessage());
            $this->dispatch('error');
        }
    }

    #[On('registrarDiagnosticoCie10')]
    public function registrarDiagnosticoCie10($id)
    {
        // $this->skipRender();
        // dd($id);
        $diagnostico = Cie10::find($id);
        if ($diagnostico) {
            if (! in_array($id, array_column($this->diagnosticosSeleccionados, 'id'))) {
                $this->diagnosticosSeleccionados[] = $diagnostico;
            } else {
                return $this->dispatch('DiagnosticoYaRegistrado');
            }
        }
        $this->cerrarModalCie10();
        $this->dispatch('cerrarSelectCie10');
        // $this->abrirModalCie10();
        // $this->dispatch('abrirSelectCie10');
        //dd($this->diagnosticosSeleccionados);
        //$this->skipRender();
    }

    // Método para eliminar un diagnóstico de la lista
    public function eliminarDiagnostico($id)
    {
        $this->diagnosticosSeleccionados = array_filter($this->diagnosticosSeleccionados, function ($diagnostico) use ($id) {
            return $diagnostico->id != $id; // Filtrar el diagnóstico con el id especificado
        });

        $this->diagnosticosSeleccionados = array_values($this->diagnosticosSeleccionados);
    }

    #[On('atras_historia_clinica')]
    public function atras_historia_clinica()
    {   //enviar a ruta que ya se atendio
        return redirect()->route('atencion_medica.historia_clinica.index', [
        'datos_agenda' => $this->datosAgenda
    ]);
    }

    #[On('guarda_y_atras')]
    public function finalizar_historia_clinica()
    {
        $this->validate();
        //dd($this->diagnosticosSeleccionados);
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
            $fecha_actual = now()->toDateString();
            $existe = Formulario::whereDate('created_at', $fecha_actual)
                ->where('id_asignacion_horario', $this->datosAgenda['id_asignacion_horario'])
                ->exists();

            if (! $existe) {
                $datos_formulario = [
                    'id_persona_enfermera' => null,
                    'id_asignacion_horario' => $this->datosAgenda['id_asignacion_horario'],
                    'id_user_created' => auth()->user()->id,
                    'id_user_updated' => auth()->user()->id,
                    'created_at' => now(),
                ];
                Formulario::create($datos_formulario);
            }

            $actualizarHC = HistoriaClinica::find($this->id_historia_clinica);

            $idsDiagnosticos = collect($this->diagnosticosSeleccionados)
                ->pluck('id')
                ->toArray();
            if ($this->diagnosticosSeleccionados == []) {
                return $this->dispatch('seleccioneDiagnosticoCie10');
            }
            // dd($idsDiagnosticos);
            $actualizarHC->HistoriaClinicaCie10()->sync($idsDiagnosticos);
            if ($actualizarHC) {
                $actualizarHC->estado_consulta = true;
                $actualizarHC->observacion_planes = $this->observacion_planes;
                $actualizarHC->motivo_consulta = $this->motivo_consulta;
                $actualizarHC->enfermedad_actual = $this->enfermedad_actual;
                $actualizarHC->objetivo = $this->objetivo;
                $actualizarHC->descriptivo = $this->descriptivo;

                $actualizarHC->save();
                $this->dispatch('soapCreada');
            }
            // --- MODIFICACIÓN DE LA RUTA CON PARÁMETROS ---
            $this->js('window.open("/consulta_externa/pdf/c_e/' . $this->id_historia_clinica . '?top=' . $this->marginTop . '&left=' . $this->marginLeft . '", "_blank")');
            // $this->js('window.open("/consulta_externa/pdf/c_e/'.$this->id_historia_clinica.'", "_blank")');
            //$this->js('window.open("/consulta_externa/pdf/c_e_descarga/'.$this->id_historia_clinica.'", "_blank")');

            return redirect()->route('atencion_medica.consulta_externa.index');
        } else {
            //dd($response->status(),$response->json());
            Log::error('Error con el servicio ASISTENCIA AGENDA', [
                'status' => $response->status(),
                'url' => $link . $this->datosAgenda['id'],
                'url' => $link . $this->datosAgenda['id'],
                'body' => $response->body(),
                'user_id' => auth()->id() ?? 'guest',
            ]);
            $this->dispatch('error');

            return redirect()->route('atencion_medica.historia_clinica.index');
        }
    }

    public function registrar_receta()
    {
        $this->dispatch('modal_registrar_receta');
    }

    public function registrar_laboratorio($tipo_lab)
    {
        $this->dispatch('modal_laboratorio', dato: $tipo_lab);
    }

    public function registrar_imagenologia($tipo_id)
    {
        $this->dispatch('modal_imagenologia', dato: $tipo_id);
    }

    public function registrar_baja_medica()
    {
        $this->dispatch('modal_baja_medica');
    }

    public function registrar_fisioterapia()
    {
        $this->dispatch('modal_fisioterapia');
    }

    public function registrar_internacion()
    {
        $this->dispatch('modal_internacion');
    }

    public function registrar_referencia()
    {
        $this->dispatch('modal_referencia');
    }

    public function registrar_interconsulta()
    {
        $this->dispatch('modal_interconsulta');
    }

    #[On('actualizarPlanes')]
    public function mostrarPlanesRegistrados()
    {   //dd('llega');
        $this->planesRegistrados = HCPlanes::with([
            'PlanReceta',
            'PlanImagenologia',
            'PlanBajaMedica',
            'PlanFisioterapia',
            'PlanInternacion',
            'PlanReferencia',
            'PlanInterconsulta',
        ])
            ->where('id_historia_clinica', $this->id_historia_clinica)
            ->get();

        //dd($this->planesRegistrados);
    }

    public function funcion_hc_planes($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde)
    {

        // para que se muestre el pdf
        //dd($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde);
        $this->mostrar_ftp_plan($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde);
    }

    public $pdf_plan;

    public function mostrar_ftp_plan($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde)
    {

        try {
            if ($id_hcplanes) {
                if ($id_plan == 1) {
                    // $receta = Receta::find($id_corresponde);
                    // $id_persona = $receta->id_persona;
                    // $nro_receta = $receta->nro_receta;

                    // $dato_per = new ServicioPersona();
                    // $datos_persona = $dato_per->datosPersona($id_persona);
                    // $matricula = $datos_persona['matricula_seguro'];
                    // $apellidos = $datos_persona['p_apellido'] . ' ' . $datos_persona['s_apellido'];

                    // $path = 'ConsultaExterna/Planes/Receta/' . $nro_receta . '_' . $id_historia_clinica . '_hc_' . $matricula . '_' . $apellidos . '.pdf';

                    // $fileContents = Storage::disk('ftp')->get($path);
                    // $pdfBase64 = base64_encode($fileContents);
                    // $this->pdf_plan = $pdfBase64;
                    $this->dispatch('abrirPopupReceta1', id: $id_corresponde);
                } elseif ($id_plan == 2) {
                    $path = 'ConsultaExterna/Planes/Laboratorio/' . 'orden_laboratorio_' . $id_corresponde . '.pdf';
                    $path = 'ConsultaExterna/Planes/Laboratorio/' . 'orden_laboratorio_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 3) {
                    $path = 'ConsultaExterna/Planes/Imagenologia/' . 'orden_imagenologia_' . $id_corresponde . '.pdf';
                    $path = 'ConsultaExterna/Planes/Imagenologia/' . 'orden_imagenologia_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 4) {
                    $path = 'ConsultaExterna/Planes/BajaMedica/' . 'orden_baja_medica_' . $id_corresponde . '.pdf';
                    $path = 'ConsultaExterna/Planes/BajaMedica/' . 'orden_baja_medica_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 5) {
                    $path = 'ConsultaExterna/Planes/Fisioterapia/' . 'orden_fisioterapia_' . $id_corresponde . '.pdf';
                    $path = 'ConsultaExterna/Planes/Fisioterapia/' . 'orden_fisioterapia_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 6) {
                    $path = 'ConsultaExterna/Planes/Internacion/' . 'orden_internacion_' . $id_corresponde . '.pdf';
                    $path = 'ConsultaExterna/Planes/Internacion/' . 'orden_internacion_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 7) {
                    $path = 'ConsultaExterna/Planes/Referencia/' . 'orden_referencia_' . $id_corresponde . '.pdf';
                    $path = 'ConsultaExterna/Planes/Referencia/' . 'orden_referencia_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 8) {
                    $path = 'ConsultaExterna/Planes/Interconsulta/' . 'orden_interconsulta_' . $id_corresponde . '.pdf';
                    $path = 'ConsultaExterna/Planes/Interconsulta/' . 'orden_interconsulta_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                }
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    public $id_persona;

    public $variable;

    public function verRecetas()
    {
        $this->id_persona = $this->id_per;
        $this->variable = 1;
        $this->dispatch('modalRecetaListado');
    }

    #[On('abrir_modal_pdf')]
    public function abrir_modal_pdf($data)
    {
        $id_receta = $data;
        $this->dispatch('abrirPopupReceta1', id: $id_receta);
    }

    public $lab;

    public function verLaboratorio()
    {
        $this->id_persona = $this->id_per;
        $this->lab = 1;
        $this->dispatch('modalLaboratorioListado');
    }

    #[On('ver_laboratorio_pdf')]
    public function ver_laboratorio_pdf($data)
    {
        $id_solicitud = $data;
        $this->dispatch('cerrarModalPlanLaboratorio');
        $this->js('window.open("/laboratorio/pdf/resultados/' . $id_solicitud . '", "_blank", "width=800,height=1000,scrollbars=yes,resizable=yes");');
        $this->js('window.open("/laboratorio/pdf/resultados/' . $id_solicitud . '", "_blank", "width=800,height=1000,scrollbars=yes,resizable=yes");');
    }

    public function verImagenologia()
    {
        $this->dispatch('enviar-id-persona-imagen', id: $this->id_per);
        $this->dispatch('modalImagenologiaListado');
    }

    public $mostrar_v1 = 0;

    public $nueva_alergia = '';

    public function mostrarInput()
    {
        $this->mostrar_v1 = 1;
        $this->nueva_alergia = '';
    }

    public function cerrarInput()
    {
        $this->mostrar_v1 = 0;
        $this->nueva_alergia = '';
        //dd('modatra');
    }

    public function registrarAlergia()
    {
        $alergiaActual = trim(strtoupper($this->alergias));
        if ($alergiaActual == '' || $alergiaActual == null || $alergiaActual == 'NINGUNO' || $alergiaActual == 'NO PRESENTA ALERGIAS') {
            $concatenarAlergias = $this->nueva_alergia;
        } else {
            $concatenarAlergias = $this->alergias . ', ' . $this->nueva_alergia;
            $concatenarAlergias = $this->alergias . ', ' . $this->nueva_alergia;
        }

        $id_user_updated = auth()->user()->id;
        $dato_per = new ServicioPersona();
        $actualizarAlergia = $dato_per->agregarAlergiaAfiliacion($this->id_per, $concatenarAlergias, $id_user_updated);
        $this->alergias = $concatenarAlergias;
        if ($actualizarAlergia) {
            $this->dispatch('alergiaActualizada');
            $this->nueva_alergia = '';
        }
    }

    public function registrarSignos()
    {
        //$this->validate();
        try {
            $signoVitalExistente = SignoVital::where('id_agenda', $this->idAgendaActual)->first();
            $datosParaGuardar = [
                'presion_arterial'        => $this->presionArterial,
                'frecuencia_respiratoria' => $this->frecuenciaRespiratoria,
                'temperatura'             => $this->temperatura,
                'frecuencia_cardiaca'     => $this->frecuenciaCardiaca,
                'saturacion_oxigeno'      => $this->saturacionOxigeno,
                'peso'                    => $this->peso,
                'talla'                   => $this->talla,

                'id_especialista'         => $this->datosAgenda['asignacion_horario_agenda']['id_especialista'] ?? null,
                'id_especialidad'         => $this->datosAgenda['asignacion_horario_agenda']['especialista_asignacion_horario']['id_especialidad'] ?? null,
                'id_user_updated' => auth()->user()->id,
            ];

            if ($signoVitalExistente) {
                $signoVitalExistente->update($datosParaGuardar);
            } else {
                $datosParaGuardar['id_agenda'] = $this->idAgendaActual;
                $datosParaGuardar['id_user_created'] =  auth()->user()->id;
                SignoVital::create($datosParaGuardar);
            }
            $this->dispatch('guardarSignosVitales');
        } catch (\Throwable $th) {
            Log::error("Error al guardar signos vitales (Agenda: {$this->idAgendaActual}): " . $th->getMessage());
            $this->dispatch('signos_vitales_error', ['message' => 'No se pudieron guardar los signos vitales.']);
            //dd($th->getMessage());
            //$this->dispatch('signos_vitales_error');
        }
    }

    public function historialSignos()
    {
        //dd("Aqui es modal historial");
    }

    public function verBajasMedicas()
    {
        $this->id_persona = $this->id_per;
        $this->dispatch('abrirModalBajaMedica');
    }
}
