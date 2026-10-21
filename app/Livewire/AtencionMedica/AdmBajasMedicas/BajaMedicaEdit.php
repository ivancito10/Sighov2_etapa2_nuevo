<?php

namespace App\Livewire\AtencionMedica\AdmBajasMedicas;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\ConsultaExterna\BajaMedica;
use App\Models\ConsultaExterna\HCPlanes;
use App\Services\ServicioPersonaInstitucion;
use App\Services\ServicioPersona;
use Livewire\Attributes\Validate;
use App\Models\ConsultaExterna\TipoBajaMedica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BajaMedicaEdit extends Component
{
    public $id_baja, $cod_baja, $fecha_creacion, $tipo_bajas, $motivo, $periodo, $nro_dias, $fecha_inicio, $fecha_fin, $fecha_inicio_mostrar, $fecha_fin_mostrar;
    public $paciente, $matricula, $ci, $sexo, $edad, $tipo_asegurado, $codigo_institucional, $instituciones, $id_tipo_baja;
    public function render()
    {
        // dd('0');
        return view('livewire.atencion-medica.adm-bajas-medicas.baja-medica-edit');
    }
    public function mount()
    {
        $this->tipo_bajas = TipoBajaMedica::all();
        // dd($this->tipo_baja);
    }
    #[On('editar_baja')]
    public function editar_baja($data)
    {
        //dd($this->tipo_bajas);
        // dd('asdsd');
        $this->id_baja = $data;
        // dd($this->id_baja);
        $this->mostrarDatosBaja($data);
        $this->dispatch('editarBajaMedicaModal');
    }

    public function mostrarDatosBaja($id_baja)
    {
        $baja_medica = BajaMedica::with('bajaMedicaPersona', 'tipoBajaMedicaBajaMedica')->find($id_baja);
        if ($baja_medica) {
            $this->cod_baja = $baja_medica->nro_baja_medica;
            $this->fecha_creacion = $baja_medica->created_at;
            $this->paciente = $baja_medica->bajaMedicaPersona->p_apellido . ' ' . $baja_medica->bajaMedicaPersona->s_apellido . ' ' . $baja_medica->bajaMedicaPersona->nombres;
            $this->matricula = $baja_medica->bajaMedicaPersona->matricula_seguro;
            $this->ci = $baja_medica->bajaMedicaPersona->ci;
            $genero = $baja_medica->bajaMedicaPersona->sexo;
            $this->sexo = $genero === 'F' ? 'Femenino' : 'Masculino';
            $this->edad = Carbon::parse($baja_medica->bajaMedicaPersona->fecha_nacimiento)->age;

            $dato_insitucional_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_insitucional_per->personaInstitucion($baja_medica->id_persona);
            $this->instituciones = implode(
                ', ',
                array_filter(
                    array_column($persona_institucion['instituciones'] ?? [], 'nombre_institucion')
                )
            );

            // dd($this->instituciones);
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($baja_medica->id_persona);
            // dd($datos_persona);
            $this->tipo_asegurado = $persona_institucion['tipo_asegurado'];
            $instituciones = $persona_institucion['instituciones'];
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $datos_persona['institucion']) {
                        $this->codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $this->codigo_institucional = null;
            }
            //dd($persona_institucion);
            $this->motivo = $baja_medica->descripcion;
            $this->periodo = Carbon::parse($baja_medica->fecha_ini_baja)->format('d/m/Y') . ' - ' . Carbon::parse($baja_medica->fecha_fin_baja)->format('d/m/Y');
            // $this->tipo_baja = $baja_medica->tipoBajaMedicaBajaMedica->nombre;
            $this->fecha_inicio_mostrar =  Carbon::parse($baja_medica->fecha_ini_baja)->format('Y-m-d');
            $this->fecha_fin_mostrar = Carbon::parse($baja_medica->fecha_fin_baja)->format('Y-m-d');
            $this->fecha_inicio = $baja_medica->fecha_ini_baja;
            $this->fecha_fin = $baja_medica->fecha_fin_baja;
            $this->nro_dias = $baja_medica->dias_baja;
            $this->id_tipo_baja = $baja_medica->id_tipo_baja;
        }
    }
    public function preguntarEditarBaja()
    {
        // dd('sadsa');
        //$this->validate();
        $this->dispatch('guardarEditarBaja');
    }

    public function rules()
    {
        return [
            'fecha_inicio_mostrar' => 'required|date|before_or_equal:fecha_fin_mostrar',
            'fecha_fin_mostrar' => 'required|date|after_or_equal:fecha_inicio_mostrar',
            'id_tipo_baja' => 'required|integer',
        ];
    }
    public function modificarNroDias()
    {
        $inicio = Carbon::parse($this->fecha_inicio_mostrar);
        $fin    = Carbon::parse($this->fecha_fin_mostrar);

        $dias = $inicio->diffInDays($fin);
        $this->nro_dias = $dias + 1;
        // dd($this->nro_dias);
    }

    #[On('actualzarBaja')]
    public function actualzarBaja()
    {
        //  $this->modificarNroDias();
        $this->validate();
        DB::beginTransaction();
        $baja = BajaMedica::find($this->id_baja);
        //dd($baja);
        if ($baja) {
            //  dd($this->nro_dias);
            // $baja->update([]);
            $baja->id_tipo_baja = $this->id_tipo_baja;
            $baja->fecha_ini_baja = $this->fecha_inicio_mostrar;
            $baja->fecha_fin_baja = $this->fecha_fin_mostrar;
            $baja->dias_baja = $this->nro_dias;
            $baja->id_user_updated = auth()->user()->id;
            // dd($baja);
            $baja->save();
            DB::commit();
            $this->dispatch('pg:eventRefresh-AdmMedBajaMedicaTable');
            $this->dispatch('actualizarBajaExitoso');
        }
    }
    public function reseteoBajas()
    {
        $this->reset('id_tipo_baja', 'fecha_inicio_mostrar');
    }
}
