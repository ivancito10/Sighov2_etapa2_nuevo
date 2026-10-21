<?php

namespace App\Livewire\Administracion\RolesPermisos;

use Livewire\Component;
use App\Models\Administracion\Role;


class PermisosModal extends Component
{
    public $roleId;
    public $enabled;

    public function mount($roleId, $enabled)
    {
        $this->roleId = $roleId;
        $this->enabled = $enabled;
    }

    public function toggle()
    {
        $role = Role::find($this->roleId);
        $role->enabled = !$this->enabled;
        $role->save();

        $this->enabled = !$this->enabled;
        $this->emit('roleStatusChanged', $this->roleId, $this->enabled);
    }

    public function render()
    {
        return view('livewire.Administracion.roles-permisos.permisos-modal');
    }

}
