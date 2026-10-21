<?php

namespace App\Livewire\AtencionMedica\AdmMedReceta;

use App\Models\ConsultaExterna\TipoConsulta;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class AdmMedRecetaMain extends Component
{
    public $tipo_consulta_datos, $num_med_receta, $nom_tipo_consulta, $id;
    public function render()
    {
        return view('livewire.atencion-medica.adm-med-receta.adm-med-receta-main');
    }

    /*public function mount()
    {
        $pruebasRegeneracion = new RegenerarMovimientos();
        //$pruebasRegeneracion->regenerarMovimientosMedicamentoStockAlmacen(23);
        $pruebasRegeneracion->regenerarMovimientosMedicamentoStockFarmacia(23);
    }*/

    #[On('editarAdmMedReceta')]
    public function editarAdmMedReceta()
    {
        try {
            $tipo = $this->tipo_consulta_datos;

            if (!$tipo) {
                Log::warning('TipoConsulta no encontrado', [
                    'id_tipo_consulta' => $this->id_tipo_consulta,
                ]);
                $this->dispatch('error');
            }
            $tipo->max_med_receta = $this->num_med_receta;
            $tipo->save();
            $this->dispatch('datosModificados');
            $this->dispatch('pg:eventRefresh-AdmMedRecetaTable');
        } catch (\Throwable $e) {
            Log::error('Ocurrió un error al actualizar num_med_receta', [
                'id_tipo_consulta' => $this->id_tipo_consulta,
                'num_med_receta'   => $this->num_med_receta,
                'error'            => $e->getMessage(),
            ]);
            $this->dispatch('error');
        }
    }

    #[On('abrirModalEditarMedReceta')]
    public function abrirModalEditarMedReceta($data)
    {
        $this->tipo_consulta_datos = TipoConsulta::find($data);
        $this->nom_tipo_consulta = $this->tipo_consulta_datos->nombre;
        $this->num_med_receta = $this->tipo_consulta_datos->max_med_receta;
        $this->dispatch('abrirModalAdmMedicamentos');
    }

    #[On('cambiar_estado_receta')]
    public function cambiar_estado_receta($data)
    {
        $this->id = $data;
        $this->dispatch('alerta_cambiar_estado');
    }
    #[On('funcion_cambia_estado')]
    public function funcion_cambia_estado()
    {
        $tipo_consulta = TipoConsulta::find($this->id);
        if ($tipo_consulta != null) {
            $tipo_consulta->estado = !$tipo_consulta->estado;
            $tipo_consulta->save();
            $this->dispatch('pg:eventRefresh-AdmMedRecetaTable');
            $this->dispatch('estado_cambiado');
        }
    }
}
