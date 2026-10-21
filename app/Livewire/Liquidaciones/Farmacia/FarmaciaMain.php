<?php

namespace App\Livewire\Liquidaciones\Farmacia;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use App\Services\DatosEspecialista;
use Illuminate\Support\Carbon;
class FarmaciaMain extends Component
{

    public $nro_receta, $receta, $datos_persona, $codigo_institucional, $medicamentos = [],
        $nombre_especialista, $especialidad, $id_estado, $tipo_consulta;
    public $usuario_registra, $fecha_transcripcion, $fecha_entrega;
    public $fecha_registro, $dispensado = false, $usuario_dispensa, $fecha_dispensa, $fue_anulado = false, $usuario_anula, $fecha_anula;

    public function render()
    {
        return view('livewire.liquidaciones.farmacia.farmacia-main');
    }

    #[On('detalleReceta')]
    public function detalleReceta($data)
    {
        //dd('pantalla de recetas');
        $this->resetearReceta();
        $this->mostrarDatosReceta($data);
        $this->dispatch('abrirRecetaLiquidacion');
    }

    public function resetearReceta()
    {
        $this->reset('usuario_registra', 'dispensado', 'fecha_registro', 'fue_anulado');
    }


    public function mostrarDatosReceta($id)
    {
        $this->receta = Receta::with(
            'usuarioCreador',
            'usuarioDispensaReceta',
            'usuarioAnuladorReceta',
            'personaTranscribeReceta'
        )
            ->where('id', $id)->first();
        //dd($this->receta);
        $this->nro_receta = $this->receta->nro_receta;
        $this->id_estado = $this->receta->id_estado_validacion;
        $this->tipo_consulta = $this->receta->tipoConsultaReceta->nombre;;

        $dato_per = new ServicioPersona();
        $this->datos_persona = $dato_per->datosPersona($this->receta->id_persona);
        //dd($this->datos_persona);


        $dato_inst = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_inst->personaInstitucion($this->receta->id_persona);
        $instituciones = $persona_institucion['instituciones'];
        $this->codigo_institucional = $instituciones[0]['codigo_institucional'] ?? '';
        $this->fecha_transcripcion = $this->receta->fecha_transcripcion;
        $this->fecha_entrega = $this->receta->fecha_entrega ? Carbon::parse($this->receta->fecha_entrega)->format('d/m/Y') : 'Sin entregar';
        // dd($this->receta);
        $this->medicamentos = RecetaMedicamento::where('id_receta', $id)->get();

        // Servicio de Especialista
        $dato_esp = new DatosEspecialista();
        $datos_esp = $dato_esp->datosEspecialista($this->receta->id_especialista);
        $this->nombre_especialista = $datos_esp['persona_especialista']['p_apellido'] . ' ' . $datos_esp['persona_especialista']['s_apellido'] . ' ' . $datos_esp['persona_especialista']['nombres'];
        $this->especialidad = $datos_esp['especialidad_especialista']['especialidad'];

        //datos para el seguimieto
        $this->usuario_registra = $this->receta->usuarioCreador->name ?? 'SIN DATO';
        $this->fecha_registro = $this->receta->created_at;
        if ($this->receta->id_usuario_dispensa) {
            //dd('asdas');
            $this->dispensado = true;
            $this->usuario_dispensa = $this->receta->usuarioDispensaReceta->name ?? 'SIN DATO';
            $this->fecha_dispensa = $this->receta->fecha_dispensado;
        }

        if ($this->receta->id_usuario_anula) {
            //dd('asdas');
            $this->fue_anulado = true;
            $this->usuario_anula = $this->receta->usuarioAnuladorReceta->name ?? 'SIN DATO';
            $this->fecha_anula = $this->receta->fecha_anulado;
        }

        //dd($this->fecha_registro);
    }
}
