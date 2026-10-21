<?php

namespace App\Livewire\Administracion\RolesPermisos;

use Livewire\Component;

class AsignarPermisos extends Component
{
    public $idUsuario;
    public function render()
    {
        return view('livewire.Administracion.roles-permisos.asignar-permisos');
    }
}
