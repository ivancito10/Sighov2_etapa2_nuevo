<?php

namespace App\Livewire\Administracion\RolesPermisos;
use App\Models\User;
use App\Models\Administracio\Permission;
use App\Models\Administracion\Role;
use Livewire\Component;

class RolesPermisosList extends Component
{
    
    public function listarUsuarios(){
    //
        $usuarios=Role::all();
        return $usuarios;
    }
    public function render()
    {
        return view('livewire.Administracion.roles-permisos.roles-permisos-list',[
            'DatosUsuarios'=>$this->listarUsuarios()
        ]);
    }
}
