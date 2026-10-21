<?php

namespace App\Livewire\AtencionMedica\OtrasConsultas;

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
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\TipoImagenologia;
use App\Services\ObtenerToken;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistroSoap extends Component
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

    public $motivo_consulta;

    public $enfermedad_actual;

    public $objetivo;

    public $descriptivo;

    public $tipo_consulta;

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

    public $pdf_vista;
    public bool $mostrandoModalCie10 = false;


    public function render()
    {
        return view('livewire.atencion-medica.otras-consultas.registro-soap');
    }

    public function mount()
    {
        //$this->datosAgenda = json_decode(urldecode(request()->query('data')), true);
        $this->datosAgenda = session('agenda_data');
        $this->id_historia_reconsulta = request()->query('data');
        $this->reconsulta = filter_var(request()->input('reconsulta'), FILTER_VALIDATE_BOOLEAN);
        $this->tipo_consulta = request()->query('tipo_at');

        $this->mostrarDatos($this->datosAgenda);
        $this->datos_cie_10 = Cie10::all();
        $this->tipos_imagenologia = TipoImagenologia::orderBy('nombre', 'asc')->get();

        //PREGUNTAR SI ES MEDICO HABILITADO
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

    protected $rules = [
        'motivo_consulta' => 'required|string|min:1',
        'objetivo' => 'required|string|min:2',
    ];

    public function mostrarDatos($agendaData)
    {
        //dd($agendaData);
        $this->datos_paciente = Persona::find($agendaData['id']);
        $this->id_per = $this->datos_paciente->id;

        $fechaNacimiento = $this->datos_paciente?->fecha_nacimiento ? \Carbon\Carbon::parse($this->datos_paciente->fecha_nacimiento) : null;
        $this->edad = $fechaNacimiento?->age;

        $id_persona = $agendaData['id'];
        // Servicio para mostrar datos tipo asegurado e institucion del asegurado
        $dato_per = new ServicioPersona();
        $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
        $this->institucion = $datos_persona['institucion'] ?? null;
        $this->estado_vigencia = $datos_persona['estado_vigencia'];
        $this->alergias = $datos_persona['alergias'] ?? '';

        $urlFoto = $datos_persona['url_foto'] ?? '';
        // $this->foto_personal = $urlFoto;
        $this->foto_personal = $urlFoto
            ? route('imagen.proxy', ['u' => $urlFoto])
            : '';
        //$this->foto_personal = $datos_persona['url_foto'] ?? '';

        // Servicio para mostrar las instituciones de la persona
        $dato_per = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_per->personaInstitucion($id_persona);
        $this->instituciones = $persona_institucion['instituciones'];
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
    }

    #[On('atrasSubjetivo')]
    public function atrasSubjetivo()
    {
        return redirect()->route('atencion_medica.otras_consultas.create',[
            'datosAgenda' => $this->datosAgenda,
        ]);
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

    public function abrirModalCie10()
    {
        // 1. Activamos la variable para que el @if en el blade sea verdadero
        $this->mostrandoModalCie10 = true;

        // 2. Abrimos el modal (esto activa el JS de Bootstrap/AdminLTE que usas)
        $this->dispatch('modalCie10Soap');
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

            DB::beginTransaction();  // Inicia la transacción

            if ($this->diagnosticosSeleccionados == []) {
                return $this->dispatch('seleccioneDiagnosticoCie10');
            }
            $link = LinkServicio::where('nombre_servicio', 'OBTENER ESPECIALISTA POR ID USER')->pluck('link_servicio')->first();
            $response = Http::withToken(session('access_token'))->post($link, [
                'id_usuario' => auth()->user()->id,
            ]);
            //dd($link,$response,$response->json());
            $id_especialista = $response->json('data')['id'];
            $id_especialidad = $response->json('data')['especialidad_especialista']['id'];
            //dd($this->datosAgenda);
            $datos = [
                'id_agenda' => null,
                'id_tipo_consulta' => (int) $this->tipo_consulta, //consolta externa
                'id_persona' => $this->datosAgenda['id'],
                'id_especialidad' => $id_especialidad,
                'reconsulta' => $this->reconsulta,
                'id_historia_clinica' => $this->id_historia_reconsulta,
                'asistencia' => true,
                'fecha_hora' => now(),
                'id_especialista' => $id_especialista,
                'motivo_consulta' => $this->motivo_consulta,
                'enfermedad_actual' => $this->enfermedad_actual,
                'objetivo' => $this->objetivo,
                'descriptivo' => $this->descriptivo,
                'estado_consulta' => false, //todavia no se registrara como true
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];
            $datoCreado = HistoriaClinica::create($datos);
            $this->id_historia_clinica = $datoCreado->id;
            if (! empty($this->diagnosticosSeleccionados)) {
                foreach ($this->diagnosticosSeleccionados as $diagnostico) {
                    $datoCreado->HistoriaClinicaCie10()->attach($diagnostico->id);
                }
            }
            DB::commit();

            //PARA OBTENER LA INSTITUCION (SOLAMENTE LA PRIMERA) DEL ASEGURADO
            $primeraInstitucion = $this->instituciones !== [] ? $this->instituciones[array_key_first($this->instituciones)] : [];

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
            $this->dispatch('error');
        }
    }

    #[On('registrarDiagnosticoCie10')]
    public function registrarDiagnosticoCie10($id)
    {
        // dd($id);
        $diagnostico = Cie10::find($id);
        if ($diagnostico) {
            if (! in_array($id, array_column($this->diagnosticosSeleccionados, 'id'))) {
                $this->diagnosticosSeleccionados[] = $diagnostico;
            } else {
                return $this->dispatch('DiagnosticoYaRegistradoSoap');
            }
        }
        $this->cerrarModalCie10();

        $this->dispatch('cerrarCie10Soap');
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
    {
        return redirect()->route('atencion_medica.otras_consultas.create');
    }

    #[On('guarda_y_atras')]
    public function finalizar_historia_clinica()
    {
        $this->validate();
        try {
            $actualizarHC = HistoriaClinica::find($this->id_historia_clinica);
            $idsDiagnosticos = collect($this->diagnosticosSeleccionados)
                ->pluck('id')
                ->toArray();
            if ($this->diagnosticosSeleccionados == []) {
                return $this->dispatch('seleccioneDiagnosticoCie10');
            }
            $actualizarHC->HistoriaClinicaCie10()->sync($idsDiagnosticos);
            if ($actualizarHC) {
                $actualizarHC->estado_consulta = true;
                $actualizarHC->observacion_planes = $this->observacion_planes;
                $actualizarHC->motivo_consulta = $this->motivo_consulta;
                $actualizarHC->enfermedad_actual = $this->enfermedad_actual;
                $actualizarHC->objetivo = $this->objetivo;
                $actualizarHC->descriptivo = $this->descriptivo;
                $actualizarHC->save();
            }
            $this->dispatch('soapCreada');
            $this->js('window.open("/consulta_externa/pdf/c_e/' . $this->id_historia_clinica . '", "_blank")');

            return redirect()->route('atencion_medica.otras_consultas.create');
        } catch (Exception $e) {
            Log::error('Error al finalizar la historia clinica', $e);
            $this->dispatch('error');

            return redirect()->route('atencion_medica.otras_consultas.create');
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
    {   //dd('llega');
        $this->dispatch('modal_interconsulta');
    }

    #[On('actualizarPlanes')]
    public function mostrarPlanesRegistrados()
    {
        $this->planesRegistrados = HCPlanes::with([
            'PlanReceta',
            'PlanImagenologia',
            'PlanBajaMedica',
            'PlanFisioterapia',
            'PlanInternacion',
            'PlanReferencia',
        ])
            ->where('id_historia_clinica', $this->id_historia_clinica)
            ->get();
    }

    public function funcion_hc_planes($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde)
    {

        // para que se muestre el pdf
        $this->mostrar_ftp_plan($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde);
        //$this->dispatch('modal_ftp_plan');
    }

    public $pdf_plan;

    public function mostrar_ftp_plan($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde)
    {

        try {
            if ($id_hcplanes) {
                //dd($id_hcplanes, $id_historia_clinica, $id_plan, $id_corresponde);
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
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 3) {
                    $path = 'ConsultaExterna/Planes/Imagenologia/' . 'orden_imagenologia_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 4) {
                    $path = 'ConsultaExterna/Planes/BajaMedica/' . 'orden_baja_medica_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 5) {
                    $path = 'ConsultaExterna/Planes/Fisioterapia/' . 'orden_fisioterapia_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 6) {
                    $path = 'ConsultaExterna/Planes/Internacion/' . 'orden_internacion_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 7) {
                    $path = 'ConsultaExterna/Planes/Referencia/' . 'orden_referencia_' . $id_corresponde . '.pdf';
                    $fileContents = Storage::disk('ftp')->get($path);
                    $pdfBase64 = base64_encode($fileContents);
                    $this->pdf_plan = $pdfBase64;
                    $this->dispatch('modal_ftp_plan');
                } elseif ($id_plan == 8) {
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

    public function buscarPersona($carnet)
    {
        $token = session('access_token');
        try {
            $link_servicio = LinkServicio::where('nombre_servicio', 'OBTENER DATOS PERSONA')->first();
            $url = $link_servicio->link_servicio . '/' . $carnet;
            // dd($url);
            $response = Http::withToken($token)->get($url);
            // dd($response);
            if ($response->successful()) {
                //$this->esPersonalSSu = true;
                $personaData = $response->json();

                return [
                    'fotoPersonaBase64' => $personaData['fotoPersonaBase64'],
                    'afiliado' => $personaData['persona']['afiliado'],
                    'alergias' => $personaData['persona']['alergias'],
                ];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public $id_persona;

    public $variable;

    public function verRecetas()
    {
        $this->id_persona = $this->id_per;
        $this->variable = 4;
        $this->dispatch('modalRecetaListado');
    }

    #[On('abrir_modal_pdf_or')]
    public function abrir_modal_pdf_or($data)
    {
        $id_receta = $data;
        $this->dispatch('abrirPopupReceta1', id: $id_receta);
    }

    public $lab;

    public function verLaboratorio()
    {
        $this->id_persona = $this->id_per;
        $this->lab = 4;
        $this->dispatch('modalLaboratorioListado');
    }

    #[On('ver_laboratorio_pdf_soap')]
    public function ver_laboratorio_pdf_soap($data)
    {
        $id_solicitud = $data;
        $this->dispatch('cerrarModalPlanLaboratorio');
        $this->js('window.open("/laboratorio/pdf/resultados/' . $id_solicitud . '", "_blank", "width=800,height=1000,scrollbars=yes,resizable=yes");');
    }

    public function verImagenologia()
    {
        $this->dispatch('modalImagenologiaListado');
        $this->dispatch('enviar-id-persona-imagen', id: $this->id_per);
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

    public function verBajasMedicas()
    {
        $this->id_persona = $this->id_per;
        $this->dispatch('abrirModalBajaMedica');
    }
}
