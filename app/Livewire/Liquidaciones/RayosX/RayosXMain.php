<?php

namespace App\Livewire\Liquidaciones\RayosX;

use App\Models\ConsultaExterna\Imagenologia;
use App\Models\ConsultaExterna\ImagenologiaEstudio;

use Livewire\Component;
use Livewire\Attributes\On;

class RayosXMain extends Component
{
    public function render()
    {
        return view('livewire.liquidaciones.rayos-x.rayos-x-main');
    }


    #[On('detalleImagenologia')]
    public function detalleImagenologia($data)
    {
        $this->mostrarImagenologia($data);
        $this->dispatch('abrilRayosXLiquidacion');
    }

    public $asegurado, $matricula, $especialista, $fecha_solicitud, $hora_solicitud, $tipo_examen;
    public $img_estudios;
    public function mostrarImagenologia($id_solcitud)
    {
        //dd('dasd');
        $imagenologia = Imagenologia::with([
            'imagenologiaEstudio',
            'datosPacientePersona',
            'tipoImagenologia',
            'usuarioCreador'
        ])
            ->where('id', $id_solcitud)
            ->first();
        //dd($imagenologia);
        $this->asegurado = $imagenologia->datosPacientePersona->nombres . ' ' . $imagenologia->datosPacientePersona->p_apellido . ' ' . $imagenologia->datosPacientePersona->s_apellido;
        $this->matricula = $imagenologia->datosPacientePersona->matricula_seguro;

        $this->tipo_examen = $imagenologia->tipoImagenologia->nombre;
        $this->fecha_solicitud = $imagenologia->created_at;
        $this->especialista = $imagenologia->usuarioCreador->name;
        $this->img_estudios = ImagenologiaEstudio::with('datosEstudio.estudioTipo')
            ->where('id_sol_imagenologia', $imagenologia->id)
            ->get()
            // dd($this->img_estudios);
            ->map(function ($sol) {
                return [
                    'nombre_estudio' => $sol->datosEstudio->nombre,
                    'tipo_estudio' => $sol->datosEstudio->estudioTipo->nombre,
                ];
            })
            ->toArray();
        // dd($this->img_estudios);
    }
}
