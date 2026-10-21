<?php

namespace App\Livewire\AtencionMedica\AdmBajasMedicas;

use App\Models\ConsultaExterna\BajaMedica;
use App\Models\ConsultaExterna\HCPlanes;
use App\Services\ServicioPersonaInstitucion;
use App\Services\ServicioPersona;

use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;

class AdmBajasMedicasMain extends Component
{
    public $id_baja;
    public $baja_medica = null;
    public $cod_baja, $fecha_creacion, $incapacidad_laboral, $motivo, $periodo, $nro_dias;
    public $paciente, $matricula, $ci, $sexo, $edad, $tipo_asegurado, $codigo_institucional, $instituciones;
    public function render()
    {
        return view('livewire.atencion-medica.adm-bajas-medicas.adm-bajas-medicas-main');
    }

    #[On('anular_baja')]
    public function anular_baja($data)
    {
        //dd($data);
        $this->id_baja = $data;
        $this->dispatch('Alerta_anular');
    }
    #[On('funcion_anular_baja')]
    public function funcion_anular_baja($motivo)
    {
        //dd($motivo); 
        $baja_medica = BajaMedica::find($this->id_baja);
        if ($baja_medica != null) {
            $baja_medica->motivo_anulacion = $motivo;
            $baja_medica->fecha_anulacion = Carbon::now();
            $baja_medica->estado = null;
            $baja_medica->id_usuario_anula = auth()->user()->id;
            $baja_medica->save();
            $this->dispatch('pg:eventRefresh-AdmMedBajaMedicaTable');
            $this->dispatch('anulado_alerta');
        }
    }

    #[On('ver_baja')]
    public function ver_baja($data)
    {
        $this->resetearDAtos();
        $this->mostrarDatosBaja($data);
        $this->dispatch('modalMostrarBajaMedica');
    }

    public function mostrarDatosBaja($id_baja)
    {
        $baja_medica = BajaMedica::with('bajaMedicaPersona', 'tipoBajaMedicaBajaMedica', 'usuarioCreador', 'usuarioEditor', 'usuarioAnula')->find($id_baja);
        //dd($baja_medica);
        if ($baja_medica) {
            $this->cod_baja = $baja_medica->nro_baja_medica;
            $this->fecha_creacion = $baja_medica->created_at;
            $this->paciente = $baja_medica->bajaMedicaPersona->p_apellido . ' ' . $baja_medica->bajaMedicaPersona->s_apellido . ' ' . $baja_medica->bajaMedicaPersona->nombres;
            $this->matricula = $baja_medica->bajaMedicaPersona->matricula_seguro;
            $this->ci = $baja_medica->bajaMedicaPersona->ci;
            $genero = $baja_medica->bajaMedicaPersona->sexo;
            $this->sexo = $genero === 'F' ? 'Femenino' : 'Masculino';
            $this->edad = Carbon::parse($baja_medica->bajaMedicaPersona->fecha_nacimiento)->age;
            $this->incapacidad_laboral = $baja_medica->tipoBajaMedicaBajaMedica->nombre;

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
            $this->nro_dias = $baja_medica->dias_baja;
            if ($baja_medica->id_usuario_anula) {
                $this->esta_anulado = true;
                $this->usuario_anula = $baja_medica->usuarioAnula->name;
                $this->fecha_anula = $baja_medica->fecha_anulacion;
                $this->motivo_anula = $baja_medica->motivo_anulacion;
            }
            // SEGUIMIENTO 
            $this->usuarioCreador = $baja_medica->usuarioCreador->name;
            if ($baja_medica->id_user_updated) {
                $this->fue_editado = true;
                $this->usuario_edita = $baja_medica->usuarioEditor->name;
                $this->fecha_edita = $baja_medica->updated_at;
            }
        }
    }
    public $usuarioCreador, $fue_editado = false, $esta_anulado = false, $usuario_edita, $fecha_edita, $usuario_anula, $fecha_anula, $motivo_anula;
    public function resetearDAtos()
    {
        $this->reset('fue_editado', 'esta_anulado');
    }
    /*    #[On('editar_baja')]
    public function editar_baja($data)
    {
        dd('maim main');
        // $this->mostrarDatosBaja($data);
        $this->dispatch('editarBajaMedicaModal');
    } */
}
