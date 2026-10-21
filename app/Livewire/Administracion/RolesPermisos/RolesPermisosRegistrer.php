<?php

namespace App\Livewire\Administracion\RolesPermisos;

use Livewire\Component;
use App\Models\Administracion\Role;
class RolesPermisosRegistrer extends Component
{   
    public $nombre;
    public $email;
    public $name;
    public $guard_name;
    //crearClaseRolesPermisos
    protected $listeners = ['crearRolesPermisos'];

    public function render()
    {
        return view('livewire.Administracion.roles-permisos.roles-permisos-registrer');
    }
    public function crearRolesPermisos()
    {
        try{
            // Verifica si los campos obligatorios están llenos
            $this->validate([
                'name' => 'required',
                'guard_name' => 'required',
            ]);

            // Crea un nuevo registro 
            $claseRolesPermisos =Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'id_user_created' => auth()->user()->id,
                'id_user_updated' => auth()->user()->id,
            ]);
            if ($claseRolesPermisos) {
                $this->dispatch('CreadaRegisterRoles');
            } 
            $this->reset(['name', 'guard_name']);
            $this->dispatch('crearRol');
            $this->dispatch('alert');
         }
         catch (\Throwable $th) {
            $this->reset(['name', 'guard_name']);
            $this->dispatch('CanceladoRegisterRoles');            

       }
    }    
}
