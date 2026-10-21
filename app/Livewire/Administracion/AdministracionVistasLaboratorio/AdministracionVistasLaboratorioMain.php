<?php

namespace App\Livewire\Administracion\AdministracionVistasLaboratorio;

use App\Models\Laboratorio\AdministracionVistaLaboratorio;
use Livewire\Component;
use Livewire\Attributes\On;


class AdministracionVistasLaboratorioMain extends Component
{
    public $idAdmVistaLaboratorio;
    public $idUserEspecialista;

    public function render()
    {
        return view('livewire.Administracion.administracion-vistas-laboratorio.administracion-vistas-laboratorio-main');
    }

    #[On('cambiarVista')]
    public function cambiarVista($data, $especialista){
        $this->idAdmVistaLaboratorio = $data;
        $this->idUserEspecialista = $especialista;
        $this->dispatch('preguntarCambiodeVista');        
    }

    #[On('actualizarAdmVista')]
    public function actualizarAdmVista(){
        //dd('actualizando');
        $datoAdmVistaLaboratorio = AdministracionVistaLaboratorio::where('id', $this->idAdmVistaLaboratorio)
            ->where('id_user_especialista', $this->idUserEspecialista)
            ->first();

        if ($datoAdmVistaLaboratorio) {
            $datoAdmVistaLaboratorio->casillas_checks = !$datoAdmVistaLaboratorio->casillas_checks;
            $datoAdmVistaLaboratorio->id_user_updated = auth()->user()->id;
            $datoAdmVistaLaboratorio->save();
            $this->dispatch('cambioExitoso');
            $this->dispatch('pg:eventRefresh-AdmVistaLabTable');

        } else {
            $this->dispatch('sinRegistroDeEspecialista');
        }
    }
}
