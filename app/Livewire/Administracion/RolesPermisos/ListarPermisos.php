<?php

namespace App\Livewire\Administracion\RolesPermisos;

use Livewire\Component;

class ListarPermisos extends Component
{
    public $idUsuario;
    public function render()
    {
        return view('livewire.Administracion.roles-permisos.listar-permisos');
    }
}
