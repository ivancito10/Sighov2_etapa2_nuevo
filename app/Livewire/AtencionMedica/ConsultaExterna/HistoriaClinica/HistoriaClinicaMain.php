<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\BajaMedica;
use App\Models\ConsultaExterna\Fisioterapia;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Imagenologia;
use App\Models\ConsultaExterna\Interconsulta;
use App\Models\ConsultaExterna\Internacion;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\Referencia;
use App\Models\ConsultaExterna\TipoImagenologia;
use App\Models\Laboratorio\Solicitudes;
use App\Services\EspecialidadService;
use Carbon\Carbon;
use Exception;
use Livewire\Attributes\On;
use App\Services\ServicioPersona;
use Illuminate\Support\Facades\Http;
use App\Services\ServicioPersonaInstitucion;

class HistoriaClinicaMain extends Component
{
    public $idPersona;
    public $foto_personal, $institucion, $alergias;
    public $datosAgenda;
    public $datos_paciente, $edad, $datos_cie_10;
    public $hora_agenda;
    public $historialGuardado = false;
    public $listaEspecialistas;
    //public $matricula ='VML118895 - B1', $nombre_completo= 'JUANA AZURDUY DE PADILLA', $ci='10909905-1E', $sexo='FEMENINO', $INSTITUCION='UNIVERSIDAD MAYOR DE SAN ANDRES';
    public $diagnosticosCie10 = [], $diagnosticoDescriptivo, $id_historia_clinica = null;
    public $tipos_imagenologia, $tipo_img, $datosHistClinica;

    public $recetas = [], $laboratorios = [], $imagenologias = [], $bajas_medicas = [],
        $fisioterapias = [], $internaciones = [], $referencias = [], $interconsultas = [];

    public $id_tipo_consulta, $id_persona, $id_especialidad, $asistencia, $fecha_hora,
        $id_especialista, $motivo_consulta, $enfermedad_actual, $objetivo, $diagnostico, $descriptivo,
        $plan_descriptivo, $estado_consulta, $estado, $id_user_created, $created_at,
        $nombreEspecialista, $nombreEspecialidad;

    public $codigo_institucional, $nro_instituciones, $instituciones, $id_per, $estado_vigencia;
    public $datos_agenda = [];

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.historia-clinica.historia-clinica-main');
    }

    public function mount($datos_agenda = [])
    {
        $especialidadService = new EspecialidadService();
        $this->listaEspecialistas = $especialidadService->obtenerEspecialistas();
        //dd($this->listaEspecialistas);
        // $this->datosAgenda = session('agenda_data');
        $this->datosAgenda = $datos_agenda;
        // dd($this->datosAgenda);
        $this->tipos_imagenologia = TipoImagenologia::orderBy('nombre', 'asc')->get();
        $this->idPersona = $this->datosAgenda;
        $this->mostrarDatos($this->datosAgenda);
        // dd($this->datos_paciente);
    }

    public function ConsultaExternaRegister()
    {
        //$params = $this->datosAgenda;
        $flag = false; // o false, según sea el caso
        // dd($this->datosAgenda);
        return redirect()
            ->route('atencion_medica.consulta_externa.create', [
                'data' => '',
                'reconsulta' => $flag,
                'tipo_at' => 1,
                'datos_agenda' => $this->datosAgenda,
            ]);
    }

    #[On('RegistrarReconsulta')]
    public function RegistrarReconsulta($rowId)
    {
        $params = $rowId;
        $flag = true; // o false, según sea el caso

        return redirect()
            ->route('atencion_medica.consulta_externa.create', [
                'data' => $params,
                'reconsulta' => $flag,
                'tipo_at' => 1,
                'datos_agenda' => $this->datosAgenda,
            ]);
    }

    public function mostrarDatos($agendaData)
    {
        $this->datos_paciente = Persona::find($agendaData['id_persona'] ?? 1);
        $fechaNacimiento = $this->datos_paciente?->fecha_nacimiento ? \Carbon\Carbon::parse($this->datos_paciente->fecha_nacimiento) : null;
        $this->edad = $fechaNacimiento?->age;
        $this->hora_agenda = $agendaData['hora_agenda'];

        $id_persona = $agendaData['id_persona'];
        $this->id_per = $id_persona;

        $dato_per = new ServicioPersona();
        $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
        $this->estado_vigencia = $datos_persona['estado_vigencia'];
        //dd($datos_persona);
        $this->alergias = $datos_persona['alergias'] ?? ' ';
        //$this->foto_personal = $datos_persona['url_foto'] ?? ''; 
        $urlFoto = $datos_persona['url_foto'] ?? '';

        // esto descomentar para que de la foto en local
        // $this->foto_personal = $urlFoto;
        $this->foto_personal = $urlFoto
            ? route('imagen.proxy', ['u' => $urlFoto])
            : '';

        $this->institucion = $datos_persona['institucion'] ?? null;


        // Servicio para mostrar las instituciones de la persona
        $dato_per = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_per->personaInstitucion($id_persona);
        //dd($persona_institucion);
        // Asignar array vacío si no viene 'instituciones'
        $this->instituciones = $persona_institucion['instituciones'] ?? [];
        $this->nro_instituciones = count($this->instituciones);

        $this->codigo_institucional = null;
        if (!empty($this->instituciones)) {
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

    #[On('verHistoriaClinica')]
    public function verHistoriaClinica($rowId)
    {
        $this->id_historia_clinica = $rowId;
        //dd($this->id_historia_clinica);
        $this->mostrarDatosHistoriaClinica($this->id_historia_clinica);
        $this->dispatch('modalMostrarHistoriaClinica');
    }

    public function mostrarDatosHistoriaClinica($id)
    {
        try {
            $historia = HistoriaClinica::find($id);
            //dd($this->listaEspecialistas[0]);
            if ($historia) {
                $this->id_tipo_consulta = $historia->historiaClinicaTipoConsulta->nombre;
                // $this->id_persona = $historia->HistoriaClinicaPersona->nombres . ' ' . $historia->HistoriaClinicaPersona->p_apellido
                //     . ' ' . $historia->HistoriaClinicaPersona->s_apellido;
                $this->id_persona = $historia->id_persona;
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
        }
    }

    public function atras()
    {
        return redirect()->route('atencion_medica.consulta_externa.index');
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

    public function mostrarModalInterconsulta()
    {
        $this->interconsultas = [];
        $listaInterconsulta = HCPlanes::where('id_historia_clinica', $this->id_historia_clinica)
            ->where('id_plan', 8)
            ->get();
        foreach ($listaInterconsulta as $plan) {
            $interconsulta = Interconsulta::find($plan->id_corresponde);
            $this->interconsultas[] = $interconsulta;
            $especialidad = collect($this->listaEspecialistas)
                ->firstWhere('id_especialidad', $interconsulta->id_especialidad);
            if ($especialidad) {
                $interconsulta->nombre_especialidad = $especialidad['especialidad_especialista']['especialidad'] ?? 'No definida';
            }
        }
        //dd($this->listaEspecialistas, $listaInterconsulta,$this->interconsultas);
        $this->dispatch('modalMostrarInterconsulta');
    }

    public $variable;
    public function verHistorialReceta()
    {
        $this->id_persona = $this->id_per;
        $this->variable = 2;
        $this->dispatch('modalRecetaListado');
    }


    #[On('abrir_modal_pdf_hc')]
    public function abrir_modal_pdf_hc($data)
    {
        $id_receta = $data;
        $this->dispatch('abrirPopupReceta1', id: $id_receta);
    }

    public $lab;
    public function verHistorialLaboratorio()
    {
        $this->id_persona = $this->id_per;
        $this->lab = 2;
        $this->dispatch('modalLaboratorioListado');
    }
    #[On('ver_laboratorio_pdf_main')]
    public function ver_laboratorio_pdf_main($data)
    {
        $id_solicitud = $data;
        //dd($id_solicitud);
        $this->dispatch('cerrarModalPlanLaboratorio');
        $this->js('window.open("/laboratorio/pdf/resultados/' . $id_solicitud . '", "_blank", "width=800,height=1000,scrollbars=yes,resizable=yes");');
    }

    public function verHistorialImagenologia()
    {
        $this->dispatch('enviar-id-persona-imagen', id: $this->id_per);
        $this->dispatch('modalImagenologiaListado');
    }

    public $mostrar_v1 = 0, $nueva_alergia = '';
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
        if ($alergiaActual == "" || $alergiaActual == null || $alergiaActual == "NINGUNO" || $alergiaActual == "NO PRESENTA ALERGIAS") {
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


    #[On('abrir_copia_historia')]
    public function abrir_copia_historia($rowId)
    {
        //  dd($rowId);
        $this->js('window.open("/consulta_externa/pdf/c_e_copia/' . $rowId . '", "_blank")');
    }

    #[On('abrir_copia_original')]
    public function abrir_copia_original($rowId)
    {
        $this->js('window.open("/consulta_externa/pdf/copia_recuperada/' . $rowId . '", "_blank")');
    }

    public function verBajasMedicas()
    {
        $this->id_persona = $this->id_per;
        $this->dispatch('abrirModalBajaMedica');
    }
}
